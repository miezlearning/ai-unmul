@echo off
echo [1/2] Compiling Tailwind CSS and DaisyUI...
cd ..
call npx @tailwindcss/cli -i resources/css/app.css -o go-app/static/css/app.css --minify
cd go-app
echo [2/2] Building standalone Go binary (ai-unmul.exe)...
go build -ldflags="-s -w" -o ai-unmul.exe
echo Done! Run .\ai-unmul.exe to start the server.
