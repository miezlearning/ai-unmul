import pypdf

pdf_path = r'C:\Users\Miez\.gemini\antigravity\brain\9ae60431-3fe3-45ec-9f5d-32eaac2d8f5a\.user_uploaded\media_1793907271787_5ee84449.pdf'
reader = pypdf.PdfReader(pdf_path)
print('Total pages:', len(reader.pages))

found = False
for i, page in enumerate(reader.pages):
    text = page.extract_text()
    for line in text.split('\n'):
        if 'visi' in line.lower() or 'misi' in line.lower() or 'tujuan' in line.lower():
            found = True
            print(f'P{i+1}: {line}')

if not found:
    print('None found')

