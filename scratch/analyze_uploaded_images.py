import os, glob
from PIL import Image
import numpy as np

files = glob.glob('C:/Users/Miez/.gemini/antigravity/brain/9ae60431-3fe3-45ec-9f5d-32eaac2d8f5a/.user_uploaded/media_1794120122*.png')
for f in sorted(files):
    img = Image.open(f)
    arr = np.array(img)
    alpha = arr[:, :, 3]
    visible = arr[alpha > 30]
    r, g, b = visible[:, 0], visible[:, 1], visible[:, 2]
    is_colored = (np.abs(r.astype(int) - g.astype(int)) > 25) | (np.abs(r.astype(int) - b.astype(int)) > 25)
    colored_pixels = visible[is_colored]
    print(os.path.basename(f), f"Total visible: {len(visible)}, Colored: {len(colored_pixels)}")
    if len(colored_pixels) > 0:
        mean_c = colored_pixels.mean(axis=0)[:3].astype(int)
        print(f"  Mean color: {mean_c}")

