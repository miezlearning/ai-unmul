@echo off
echo Building Linux x86_64 binary for production deployment...
set GOOS=linux
set GOARCH=amd64
go build -ldflags="-s -w" -o ai-unmul-linux
echo Done! Linux binary 'ai-unmul-linux' ready for deployment.

