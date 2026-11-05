# Multi-stage build for ultra-small production image (~15MB total)
FROM golang:1.24-alpine AS builder

WORKDIR /app
COPY go.mod ./
COPY main.go ./
COPY templates/ ./templates/
COPY static/ ./static/

RUN CGO_ENABLED=0 GOOS=linux GOARCH=amd64 go build -ldflags="-s -w" -o ai-unmul-server main.go

FROM alpine:3.21
RUN apk --no-cache add ca-certificates tzdata
WORKDIR /app
COPY --from=builder /app/ai-unmul-server .

EXPOSE 8080
ENV PORT=8080
ENTRYPOINT ["/app/ai-unmul-server"]

