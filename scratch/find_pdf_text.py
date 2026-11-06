import subprocess

# Check if pip has any pdf library or if pdftotext exists
try:
    import pypdf
    print("pypdf available")
except:
    pass

try:
    import fitz
    print("PyMuPDF available")
except:
    pass

try:
    import pdfminer
    print("pdfminer available")
except:
    pass

try:
    import pypdf2
    print("pypdf2 available")
except:
    pass

