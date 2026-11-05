package main

import (
	"bytes"
	"compress/gzip"
	"context"
	"embed"
	"flag"
	"fmt"
	"html/template"
	"io"
	"io/fs"
	"log"
	"net/http"
	"os"
	"os/signal"
	"strings"
	"syscall"
	"time"
)

//go:embed all:templates
var embeddedTemplateFS embed.FS

//go:embed all:static
var embeddedStaticFS embed.FS

type PageData struct {
	Title           string
	Description     string
	MetaDescription string
	CurrentPath     string
	Year            int
}

type Server struct {
	devMode   bool
	templates map[string]*template.Template
}

func main() {
	devFlag := flag.Bool("dev", false, "Enable development mode (hot reloading of templates from disk)")
	portFlag := flag.String("port", "", "Port to run the server on (defaults to PORT env var or 8080)")
	flag.Parse()

	devMode := *devFlag || os.Getenv("ENV") == "dev" || os.Getenv("APP_ENV") == "development"

	port := *portFlag
	if port == "" {
		port = os.Getenv("PORT")
	}
	if port == "" {
		port = "8080"
	}

	srv := &Server{
		devMode:   devMode,
		templates: make(map[string]*template.Template),
	}

	if !devMode {
		if err := srv.initEmbeddedTemplates(); err != nil {
			log.Fatalf("Failed to initialize templates: %v", err)
		}
		log.Println("Templates pre-compiled and cached into memory.")
	} else {
		log.Println("Development mode active: templates will be reloaded on each request.")
	}

	mux := http.NewServeMux()

	// Static assets handler
	var staticFSHandler http.Handler
	if devMode {
		staticFSHandler = http.FileServer(http.Dir("static"))
	} else {
		subStatic, err := fs.Sub(embeddedStaticFS, "static")
		if err != nil {
			log.Fatalf("Failed to create static sub-filesystem: %v", err)
		}
		staticFSHandler = http.FileServer(http.FS(subStatic))
	}

	// Route static assets
	mux.Handle("GET /static/", http.StripPrefix("/static/", srv.staticMiddleware(staticFSHandler)))

	// Favicon shortcut
	mux.HandleFunc("GET /favicon.ico", func(w http.ResponseWriter, r *http.Request) {
		r.URL.Path = "/static/favicon.ico"
		srv.staticMiddleware(staticFSHandler).ServeHTTP(w, r)
	})

	// Page routes
	mux.HandleFunc("GET /{$}", srv.handleHome)
	mux.HandleFunc("GET /profil/tentang", srv.handleProfilTentang)
	mux.HandleFunc("GET /profil/kabinet", srv.handleProfilKabinet)
	mux.HandleFunc("GET /profil/dari-masa-ke-masa", srv.handleProfilDariMasaKeMasa)

	// Health check endpoint for monitoring/Docker/k8s
	mux.HandleFunc("GET /healthz", func(w http.ResponseWriter, r *http.Request) {
		w.Header().Set("Content-Type", "application/json")
		w.WriteHeader(http.StatusOK)
		w.Write([]byte(`{"status":"ok","runtime":"go1.24"}`))
	})

	// Chain middlewares: Security -> Gzip -> Logging -> Handler
	handler := srv.loggingMiddleware(srv.gzipMiddleware(srv.securityHeadersMiddleware(mux)))

	httpServer := &http.Server{
		Addr:         ":" + port,
		Handler:      handler,
		ReadTimeout:  5 * time.Second,
		WriteTimeout: 10 * time.Second,
		IdleTimeout:  120 * time.Second,
	}

	// Graceful shutdown handling
	stop := make(chan os.Signal, 1)
	signal.Notify(stop, os.Interrupt, syscall.SIGTERM)

	go func() {
		log.Printf("==================================================")
		log.Printf(" AI UNMUL High-Performance Web Server")
		log.Printf(" Runtime : Go 1.24 (Compiled Native Binary)")
		log.Printf(" Mode    : %s", map[bool]string{true: "Development (Disk Reload)", false: "Production (Embedded Binary)"}[devMode])
		log.Printf(" Server  : http://localhost:%s", port)
		log.Printf("==================================================")
		if err := httpServer.ListenAndServe(); err != nil && err != http.ErrServerClosed {
			log.Fatalf("Server startup failed: %v", err)
		}
	}()

	<-stop
	log.Println("\nShutting down server gracefully...")

	ctx, cancel := context.WithTimeout(context.Background(), 5*time.Second)
	defer cancel()

	if err := httpServer.Shutdown(ctx); err != nil {
		log.Printf("Server shutdown error: %v", err)
	}
	log.Println("Server gracefully stopped.")
}

func (s *Server) initEmbeddedTemplates() error {
	pages := []string{
		"beranda",
		"profil_tentang",
		"profil_kabinet",
		"profil_dari_masa_ke_masa",
	}

	for _, page := range pages {
		tmpl, err := template.ParseFS(
			embeddedTemplateFS,
			"templates/layouts/base.html",
			"templates/components/navbar.html",
			"templates/components/footer.html",
			"templates/pages/"+page+".html",
		)
		if err != nil {
			return fmt.Errorf("parsing template %s: %w", page, err)
		}
		s.templates[page] = tmpl
	}
	return nil
}

func (s *Server) getTemplate(page string) (*template.Template, error) {
	if !s.devMode {
		tmpl, ok := s.templates[page]
		if !ok {
			return nil, fmt.Errorf("template not found: %s", page)
		}
		return tmpl, nil
	}

	// In dev mode, parse from local disk files on every request
	return template.ParseFiles(
		"templates/layouts/base.html",
		"templates/components/navbar.html",
		"templates/components/footer.html",
		"templates/pages/"+page+".html",
	)
}

func (s *Server) render(w http.ResponseWriter, r *http.Request, page string, data PageData) {
	if data.Year == 0 {
		data.Year = time.Now().Year()
	}
	data.CurrentPath = r.URL.Path

	tmpl, err := s.getTemplate(page)
	if err != nil {
		log.Printf("Template resolution error: %v", err)
		http.Error(w, "500 Internal Server Error", http.StatusInternalServerError)
		return
	}

	var buf bytes.Buffer
	if err := tmpl.ExecuteTemplate(&buf, "base", data); err != nil {
		log.Printf("Template execution error for %s: %v", page, err)
		http.Error(w, "500 Internal Server Error", http.StatusInternalServerError)
		return
	}

	w.Header().Set("Content-Type", "text/html; charset=utf-8")
	buf.WriteTo(w)
}

// Handlers
func (s *Server) handleHome(w http.ResponseWriter, r *http.Request) {
	s.render(w, r, "beranda", PageData{
		Title:           "Beranda",
		Description:     "Association of Informatics Universitas Mulawarman",
		MetaDescription: "Wadah berkumpul, bertumbuh, dan berkarya mahasiswa Informatika Universitas Mulawarman.",
	})
}

func (s *Server) handleProfilTentang(w http.ResponseWriter, r *http.Request) {
	s.render(w, r, "profil_tentang", PageData{
		Title:           "Tentang AI",
		Description:     "Tentang AI UNMUL",
		MetaDescription: "Mengenal lebih dekat Association of Informatics Universitas Mulawarman.",
	})
}

func (s *Server) handleProfilKabinet(w http.ResponseWriter, r *http.Request) {
	s.render(w, r, "profil_kabinet", PageData{
		Title:           "Kabinet",
		Description:     "Struktur Kabinet AI UNMUL",
		MetaDescription: "Struktur kepengurusan dan badan pengurus harian AI UNMUL.",
	})
}

func (s *Server) handleProfilDariMasaKeMasa(w http.ResponseWriter, r *http.Request) {
	s.render(w, r, "profil_dari_masa_ke_masa", PageData{
		Title:           "Dari Masa ke Masa",
		Description:     "Perjalanan AI UNMUL Dari Masa ke Masa",
		MetaDescription: "Sejarah dan rekam jejak kiprah AI UNMUL sejak 2009 hingga kini.",
	})
}

// Middlewares
func (s *Server) securityHeadersMiddleware(next http.Handler) http.Handler {
	return http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
		w.Header().Set("X-Content-Type-Options", "nosniff")
		w.Header().Set("X-Frame-Options", "SAMEORIGIN")
		w.Header().Set("Referrer-Policy", "strict-origin-when-cross-origin")
		next.ServeHTTP(w, r)
	})
}

func (s *Server) staticMiddleware(next http.Handler) http.Handler {
	return http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
		if s.devMode {
			w.Header().Set("Cache-Control", "no-cache, no-store, must-revalidate")
		} else {
			// Aggressive caching in production for static assets (1 day)
			w.Header().Set("Cache-Control", "public, max-age=86400, immutable")
		}
		next.ServeHTTP(w, r)
	})
}

type statusResponseWriter struct {
	http.ResponseWriter
	statusCode int
}

func (rw *statusResponseWriter) WriteHeader(code int) {
	rw.statusCode = code
	rw.ResponseWriter.WriteHeader(code)
}

func (s *Server) loggingMiddleware(next http.Handler) http.Handler {
	return http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
		start := time.Now()
		rw := &statusResponseWriter{ResponseWriter: w, statusCode: http.StatusOK}
		next.ServeHTTP(rw, r)
		duration := time.Since(start)

		log.Printf("[%s] %s %d - %v", r.Method, r.URL.Path, rw.statusCode, duration)
	})
}

type gzipResponseWriter struct {
	io.Writer
	http.ResponseWriter
}

func (w gzipResponseWriter) Write(b []byte) (int, error) {
	return w.Writer.Write(b)
}

func (s *Server) gzipMiddleware(next http.Handler) http.Handler {
	return http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
		if !strings.Contains(r.Header.Get("Accept-Encoding"), "gzip") {
			next.ServeHTTP(w, r)
			return
		}

		w.Header().Set("Content-Encoding", "gzip")
		w.Header().Set("Vary", "Accept-Encoding")
		gz := gzip.NewWriter(w)
		defer gz.Close()

		gzw := gzipResponseWriter{Writer: gz, ResponseWriter: w}
		next.ServeHTTP(gzw, r)
	})
}

