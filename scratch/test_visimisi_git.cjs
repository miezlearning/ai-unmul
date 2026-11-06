const fs = require('fs');

// Generate test HTML file to verify the layout & SVG alignment
const html = `<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Git Tree Visi Misi Test</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .git-stage-grid {
            background-image: 
                linear-gradient(to right, rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 36px 36px;
        }
        .font-mono-code {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        }
    </style>
</head>
<body class="bg-[#050608] text-slate-100 min-h-screen p-10 font-sans">
    <div class="max-w-5xl mx-auto">
        <h1 class="text-3xl font-black text-center mb-10 text-white">Visi &amp; Misi Git Tree Prototype</h1>
        
        <div id="stage" class="relative rounded-3xl border border-white/10 bg-[#0b0d13] p-8 sm:p-14 overflow-hidden git-stage-grid shadow-2xl">
            <!-- Center SVG Spine -->
            <svg id="svgSpine" class="absolute left-1/2 -translate-x-1/2 top-0 h-full w-40 pointer-events-none z-10 overflow-visible" aria-hidden="true">
                <!-- Defs for glow -->
                <defs>
                    <filter id="branchGlow" x="-20%" y="-20%" width="140%" height="140%">
                        <feGaussianBlur stdDeviation="2.5" result="blur" />
                        <feMerge>
                            <feMergeNode in="blur" />
                            <feMergeNode in="SourceGraphic" />
                        </feMerge>
                    </filter>
                </defs>

                <!-- Top segment: Red S-curve -->
                <path id="pathTop" d="" fill="none" stroke="#e11d48" stroke-width="2.5" stroke-linecap="round" filter="url(#branchGlow)" />
                
                <!-- Fork left: Red curve -->
                <path id="pathForkLeft" d="" fill="none" stroke="#e11d48" stroke-width="2.5" stroke-linecap="round" filter="url(#branchGlow)" />

                <!-- Fork right: Green curve -->
                <path id="pathForkRight" d="" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" filter="url(#branchGlow)" />

                <!-- Bottom segment: Red/Green curve -->
                <path id="pathBottom" d="" fill="none" stroke="#e11d48" stroke-width="2.5" stroke-linecap="round" filter="url(#branchGlow)" />

                <!-- Nodes -->
                <circle id="dot1" cx="80" cy="0" r="5" fill="#e11d48" filter="url(#branchGlow)" />
                <circle id="dot2" cx="80" cy="0" r="4.5" fill="#10b981" filter="url(#branchGlow)" />
                <circle id="dot3" cx="50" cy="0" r="4.5" fill="#e11d48" filter="url(#branchGlow)" />
                <circle id="dot4" cx="110" cy="0" r="4.5" fill="#0b0d13" stroke="#10b981" stroke-width="2.5" filter="url(#branchGlow)" />
                <circle id="dotMerge" cx="80" cy="0" r="4" fill="#10b981" />
                <circle id="dot5" cx="80" cy="0" r="4.5" fill="#e11d48" filter="url(#branchGlow)" />
                <circle id="dot6" cx="80" cy="0" r="4.5" fill="#10b981" filter="url(#branchGlow)" />
            </svg>

            <!-- Alternating Rows -->
            <div class="relative z-20 space-y-16 sm:space-y-24">
                
                <!-- Row 1: Visi (Left) -->
                <div id="row1" class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-24 items-center">
                    <div class="text-left md:text-right pr-0 md:pr-4">
                        <div class="font-mono-code text-xs mb-2 flex items-center md:justify-end gap-2">
                            <span class="text-slate-400">d041958</span>
                            <span class="text-rose-500 font-bold">HEAD -&gt; main</span>
                            <span class="px-1.5 py-0.5 rounded text-[11px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">tag: visi</span>
                        </div>
                        <p class="text-sm sm:text-base font-medium text-slate-200 leading-relaxed max-w-md ml-auto">
                            Menjadikan praktikum Informatika sebagai ruang belajar yang setara, menyenangkan, dan membuat mahasiswa berani mencoba.
                        </p>
                    </div>
                    <div class="hidden md:block"></div>
                </div>

                <!-- Row 2: Misi 1 (Right) -->
                <div id="row2" class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-24 items-center">
                    <div class="hidden md:block"></div>
                    <div class="text-left pl-0 md:pl-4">
                        <div class="font-mono-code text-xs mb-2 flex items-center gap-2">
                            <span class="text-slate-400">1ef330c</span>
                            <span class="text-rose-500 font-bold">feat(misi):</span>
                        </div>
                        <p class="text-sm sm:text-base text-slate-300 leading-relaxed max-w-md">
                            Menjadi jembatan antara dosen dan praktikan agar materi tersampaikan dengan bahasa yang mudah dipahami.
                        </p>
                    </div>
                </div>

                <!-- Row 3: Misi 2 (Left, on Left Branch) -->
                <div id="row3" class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-24 items-center">
                    <div class="text-left md:text-right pr-0 md:pr-4">
                        <div class="font-mono-code text-xs mb-2 flex items-center md:justify-end gap-2">
                            <span class="text-slate-400">afceac7</span>
                            <span class="text-rose-500 font-bold">feat(misi):</span>
                        </div>
                        <p class="text-sm sm:text-base text-slate-300 leading-relaxed max-w-md ml-auto">
                            Menciptakan suasana praktikum yang santai dan menyenangkan tanpa mengurangi kualitas materi.
                        </p>
                    </div>
                    <div class="hidden md:block"></div>
                </div>

                <!-- Row 4: Misi 3 (Right, on Right Branch) -->
                <div id="row4" class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-24 items-center">
                    <div class="hidden md:block"></div>
                    <div class="text-left pl-0 md:pl-4">
                        <div class="font-mono-code text-xs mb-2 flex items-center gap-2">
                            <span class="text-slate-400">6028f59</span>
                            <span class="text-rose-500 font-bold">feat(misi):</span>
                        </div>
                        <p class="text-sm sm:text-base text-slate-300 leading-relaxed max-w-md">
                            Mendampingi praktikan menguasai keterampilan teknis dengan pendekatan sesama mahasiswa.
                        </p>
                    </div>
                </div>

                <!-- Row 5: Misi 4 (Left) -->
                <div id="row5" class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-24 items-center">
                    <div class="text-left md:text-right pr-0 md:pr-4">
                        <div class="font-mono-code text-xs mb-2 flex items-center md:justify-end gap-2">
                            <span class="text-slate-400">5d4c8d1</span>
                            <span class="text-rose-500 font-bold">feat(misi):</span>
                        </div>
                        <p class="text-sm sm:text-base text-slate-300 leading-relaxed max-w-md ml-auto">
                            Menghadirkan informasi praktikum yang lengkap dan mudah diakses lewat website dan media sosial ASCII.
                        </p>
                    </div>
                    <div class="hidden md:block"></div>
                </div>

                <!-- Row 6: Misi 5 (Right) -->
                <div id="row6" class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-24 items-center">
                    <div class="hidden md:block"></div>
                    <div class="text-left pl-0 md:pl-4">
                        <div class="font-mono-code text-xs mb-2 flex items-center gap-2">
                            <span class="text-slate-400">fb76329</span>
                            <span class="text-rose-500 font-bold">feat(misi):</span>
                        </div>
                        <p class="text-sm sm:text-base text-slate-300 leading-relaxed max-w-md">
                            Membangun budaya kolaborasi, bertanya, dan berbagi ilmu tanpa rasa sungkan.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function updateSpine() {
            const stage = document.getElementById('stage');
            const stageRect = stage.getBoundingClientRect();
            
            const rows = [1, 2, 3, 4, 5, 6].map(i => {
                const el = document.getElementById('row' + i);
                const r = el.getBoundingClientRect();
                return (r.top + r.bottom) / 2 - stageRect.top;
            });

            const cx = 80;
            const leftX = 50;
            const rightX = 110;

            const [y1, y2, y3, y4, y5, y6] = rows;
            const yMerge = (y4 + y5) / 2;

            // Position Dots
            document.getElementById('dot1').setAttribute('cy', y1);
            document.getElementById('dot2').setAttribute('cy', y2);
            document.getElementById('dot3').setAttribute('cy', y3);
            document.getElementById('dot4').setAttribute('cy', y4);
            document.getElementById('dotMerge').setAttribute('cy', yMerge);
            document.getElementById('dot5').setAttribute('cy', y5);
            document.getElementById('dot6').setAttribute('cy', y6);

            // Path Top: From y1-30 to y1, then S-curve to y2
            const dTop = \`M \${cx} \${Math.max(0, y1 - 40)} L \${cx} \${y1} C \${cx} \${y1 + (y2 - y1)*0.4}, \${cx} \${y2 - (y2 - y1)*0.4}, \${cx} \${y2}\`;
            document.getElementById('pathTop').setAttribute('d', dTop);

            // Path Fork Left (Red): From y2 curves out to (leftX, y3), then curves in to yMerge
            const dForkLeft = \`M \${cx} \${y2} C \${cx} \${y2 + (y3 - y2)*0.45}, \${leftX} \${y3 - (y3 - y2)*0.45}, \${leftX} \${y3} C \${leftX} \${y3 + (yMerge - y3)*0.45}, \${cx} \${yMerge - (yMerge - y3)*0.45}, \${cx} \${yMerge}\`;
            document.getElementById('pathForkLeft').setAttribute('d', dForkLeft);

            // Path Fork Right (Green): From y2 curves out to (rightX, y4), then curves in to yMerge
            const dForkRight = \`M \${cx} \${y2} C \${cx} \${y2 + (y4 - y2)*0.45}, \${rightX} \${y4 - (y4 - y2)*0.45}, \${rightX} \${y4} C \${rightX} \${y4 + (yMerge - y4)*0.45}, \${cx} \${yMerge - (yMerge - y4)*0.45}, \${cx} \${yMerge}\`;
            document.getElementById('pathForkRight').setAttribute('d', dForkRight);

            // Path Bottom (Red to Green): From yMerge S-curve to y5, then S-curve to y6
            const dBottom = \`M \${cx} \${yMerge} C \${cx} \${yMerge + (y5 - yMerge)*0.45}, \${cx} \${y5 - (y5 - yMerge)*0.45}, \${cx} \${y5} C \${cx} \${y5 + (y6 - y5)*0.45}, \${cx} \${y6 - (y6 - y5)*0.45}, \${cx} \${y6} L \${cx} \${y6 + 40}\`;
            document.getElementById('pathBottom').setAttribute('d', dBottom);
        }

        window.addEventListener('load', updateSpine);
        window.addEventListener('resize', updateSpine);
    </script>
</body>
</html>`;

fs.writeFileSync('C:/Users/Miez/Documents/AI UNMUL/ai-unmul/scratch/prototype_visimisi.html', html);
console.log('Prototype generated!');

