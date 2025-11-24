<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport"
        content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0"/>
  <title>Exam Maker — exam Generator</title>
  @vite('resources/css/app.css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>

  <!-- libs -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/html-to-pdfmake@2.4.5/browser.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/3.0.4/purify.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/html-docx-js/dist/html-docx.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/mammoth@1.6.0/mammoth.browser.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jszip@3.10.1/dist/jszip.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>



  <!-- pdf.js (for reading PDFs only) -->
  <script src="https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.min.js"></script>
  <script>
    pdfjsLib.GlobalWorkerOptions.workerSrc =
      "https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.worker.min.js";
  </script>

  <style>
    @keyframes gradientShift { 0%{background-position:0% 50%} 50%{background-position:100% 50%} 100%{background-position:0% 50%} }
    .gradient-animated { background: linear-gradient(-45deg,#1e3a8a,#3b82f6,#60a5fa,#93c5fd,#1e40af,#1d4ed8); background-size:400% 400%; animation: gradientShift 15s ease infinite; }
    .badge { display:inline-flex; align-items:center; gap:6px; padding:4px 8px; border:1px solid #e5e7eb; border-radius:999px; background:#fff; font-size:12px }
    .fileBadge { background:#f9fafb; border:1px solid #e5e7eb; border-radius:999px; padding:4px 8px; display:flex; align-items:center; gap:6px; }
    .fileBadge button { border:none; background:none; color:#ef4444; cursor:pointer; }
    .paper { width:210mm; min-height:297mm; background:#fff; color:#111; margin:0 auto; border:1px solid #e5e7eb; border-radius:10px; padding:18mm; box-sizing:border-box; }
    .page-break { page-break-after: always; break-after: page; }
    .q { margin:8px 0 }
    .muted { color:#6b7280 }
    .small { font-size:12px }
    .spinner{ animation:spin 1s linear infinite }
    @keyframes spin { from{transform:rotate(0)} to{transform:rotate(360deg)} }

    /* table like the screenshot */
    table.tos { border-collapse: collapse; width: 100%; font-size: 13px; }
    .tos th,.tos td { border:1px solid #e5e7eb; padding:6px 8px; }
    .tos thead th { background:#f8fafc; }
    .fg-thumb { width:72px;height:54px;object-fit:cover;border:1px solid #e5e7eb;border-radius:6px; }
    
    /* From Uiverse.io by arieshiphop */ 
    #removeBtn {
     font-size: 12px;
     padding: 10px 10px; 
     margin: 10px;
     border: transparent;
     box-shadow: 2px 2px 4px rgba(0,0,0,0.4);
     background: #e74c3c;
     color: white;
     border-radius: 4px;
    }
    
    #addRowBtn, #removeRow, #rescan {
     font-size: 12px;
     padding: 10px 10px; 
     margin: 10px;
     border: transparent;
     box-shadow: 2px 2px 4px rgba(0,0,0,0.4);
     background: dodgerblue;
     color: white;
     border-radius: 4px;
    }
    /* #autoDistributeBtn,*/
    #removeRow {
        background-color: #c0392b;
    }
    
    #addRowBtn:hover, #rescan:hover, #removeBtn:hover {
     background: rgb(2,0,36);
     background: linear-gradient(90deg, rgba(30,144,255,1) 0%, rgba(0,212,255,1) 100%);
    }
    /* #autoDistributeBtn:hover,*/
    
     #removeRow:hover {
     background:  #c0392b;
     background: linear-gradient(90deg, #c0392b 0%, #e74c3c 100%);
    }
    
     #addRowBtn:active, #removeBtn:active, #removeRow:active, #rescan:active {
     transform: translate(0em, 0.2em);
    }
    /*#autoDistributeBtn:active,*/
    
    .figWrap{
      display:grid;
      grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
      gap:8px;
    }
    .figWrap label{
      display:flex;
      flex-direction:column;
      gap:4px;
    }
    .fg-thumb{
      width:100%;
      height:96px;           /* uniform tile height */
      object-fit:cover;      /* crop to fill */
      border:1px solid #e5e7eb;
      border-radius:8px;
    }
    
    .figWrap label{
      position:relative;
    }
    .figWrap .fgChk{
      position:absolute;
      top:6px; right:6px;
      width:18px; height:18px;
    }


ol { padding-left: 1.25rem; }
ol li { margin: 2px 0; }

#figureBtn {
 font-size: 8px;
 margin-left: 5px;
 padding: 5px 5px;
 border: transparent;
 box-shadow: 2px 2px 4px rgba(0,0,0,0.4);
 background: dodgerblue;
 color: white;
 border-radius: 4px;
}

#figureBtn:hover {
 background: rgb(2,0,36);
 background: linear-gradient(90deg, rgba(30,144,255,1) 0%, rgba(0,212,255,1) 100%);
}

#figureBtn:active {
 transform: translate(0em, 0.2em);
}


#file-input {
  width: 89px;
  max-width: 100%;
  font-size: 10px;
  color: #444;
  padding: 1px;
  background: #fff;
  border-radius: 10px;
  border: 1px solid rgba(8, 8, 8, 0.288);
}

#file-input::file-selector-button {
  margin-right: 20px;
  border: none;
  background: #084cdf;
  padding: 7px 15px;
  border-radius: 10px;
  color: #fff;
  cursor: pointer;
  transition: background .2s ease-in-out;
}

#file-input::file-selector-button:hover {
  background: #0d45a5;
}
  </style>
</head>
<body class="min-h-screen">
  <!-- bg -->
  <div class="absolute inset-0 gradient-animated"></div>

  <script>
    /* ==== auth gate (kept) ==== */
    const TOKEN_KEY='jwt_token', CACHE_KEY='profile_cache';
    const jwt = (localStorage.getItem(TOKEN_KEY)||'').replace(/^Bearer\s+/i,'').replace(/^"|"$/g,'');
    if (!jwt) location.replace('/login');
    function parseJwt(t){ try{ const [,p]=t.split('.'); if(!p) return {}; return JSON.parse(decodeURIComponent(atob(p.replace(/-/g,'+').replace(/_/g,'/')).split('').map(c=>'%'+('00'+c.charCodeAt(0).toString(16)).slice(-2)).join('')));}catch{return{}}}
    const payload = parseJwt(jwt); if(payload.exp && Date.now() >= payload.exp*1000){ localStorage.removeItem(TOKEN_KEY); localStorage.removeItem(CACHE_KEY); location.replace('/login'); }
    const $ = sel => document.querySelector(sel); const $$ = (sel,root=document)=>Array.from(root.querySelectorAll(sel));
    const escapeHTML = (s="")=>String(s).replace(/[&<>"']/g,m=>({ "&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#39;" }[m]));
  </script>

  <div class="flex relative z-10">
    <!-- Sidebar (kept) -->
    <aside class="w-64 bg-white shadow-2xl min-h-screen border-r border-gray-200 relative">
      <a href="/dashboard" class="flex items-center p-6 border-b border-gray-100 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 transition-all duration-200">
        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mr-3 shadow-lg">
          <img style="width:30px" src="/images/icon.png" alt="logo">
        </div>
        <h1 class="text-xl font-bold text-white">Exam Maker</h1>
      </a>

      <nav class="mt-6 px-4">
        <a href="/dashboard" class="flex items-center px-4 py-3 text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all duration-200 mb-2 group">
          <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
        </a>
        <a href="/generate-exam" class="flex items-center px-4 py-3 text-white bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg mb-2">
          <i class="fas fa-wand-magic-sparkles mr-3"></i> Generate Exam
        </a>
        <a href="/my-exams" class="flex items-center px-4 py-3 text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all duration-200 mb-2 group">
          <i class="fas fa-file-alt mr-3"></i> My Exams
        </a>
        <!--a href="/cms" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-xl mb-2"><i class="fas fa-clipboard-list mr-3"></i> CMS</a-->
      </nav>

      <!-- Profile -->
      <div style="max-width:240px" class="absolute bottom-0 w-64 p-6 border-t border-gray-100 /*bg-gray-50*/">
        <div class="flex items-center mb-4">
          <div class="w-10 h-10 rounded-full mr-3 shadow-md border border-gray-300 overflow-hidden">
            <img id="profilePic" src="/images/default-avatar.png" alt="Profile Picture" class="w-full h-full object-cover">
          </div>
          <div>
            <p id="displayName" class="font-bold text-gray-900">User</p>
            <a href="/profile" class="text-sm text-blue-600 hover:text-blue-700 transition-colors duration-200">View Profile</a>
          </div>
        </div>
        <button id="logoutBtn" class="w-full bg-white text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-100 border border-gray-200 shadow-sm transition-all duration-200 hover:shadow-md">Logout</button>
      </div>
    </aside>

    <!-- Main -->
    <main class="flex-1 p-8">
      <!-- Page header -->
      <div class="mb-6">
        <div class="flex items-center mb-4">
          <a href="/dashboard" class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mr-4 shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-200 cursor-pointer">
            <i class="fas fa-arrow-left text-blue-500 text-2xl"></i>
          </a>
          <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-1">Exam Generator</h1>
            <p class="text-gray-600">Use TOS + Bloom mapping. Materials per row → figures selection → generate sets → export/save.</p>
          </div>
        </div>
      </div>

      <!-- Alerts -->
      <div id="alertBox" class="hidden fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg"></div>

          <!-- Meta (Exam title + Model only) -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
          <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-1">Exam Title</label>
              <input id="examTitle" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="e.g., Midterm — Computer Networks (OSI Model)"/>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Model (for cleaner only)</label>
              <select id="model" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="gemini/gemini-aistudio:free">Gemini-aistudio (free)</option>
                <option value="deepseek/deepseek-r1:free">DeepSeek R1 (free)</option>
                <option value="deepseek/deepseek-chat-v3-0324:free">DeepSeek V3 (free)</option>
                <option value="mistralai/mistral-small-3.1-24b-instruct:free">Mistral 3.1 (free)</option>
              </select>
            </div>
        
            <div class="md:col-span-3">
              <span class="inline-flex items-center gap-2 text-sm px-2 py-1 rounded bg-blue-50 text-blue-700 border border-blue-200">
                <i class="fas fa-circle-check"></i>
                Mode: <strong>Multiple Choice only</strong>
              </span>
            </div>
          </div>
        </div>


      <!-- TOS + Bloom rows + Global totals controls -->
      <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-100">
          <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
            <h2 class="text-xl font-bold text-gray-900">Most Essential Learning Competencies <span id="autoDistributeBtn">(per Topic)</span></h2>
            <div class="flex flex-wrap items-center gap-2">
              <span style="display:none" class="badge">Easy <input id="gEasy" type="number" class="border rounded px-2 py-1" value="20" min="0" style="width:80px"></span>
              <span style="display:none" class="badge">Average <input id="gAvg" type="number" class="border rounded px-2 py-1" value="20" min="0" style="width:80px"></span>
              <span style="display:none" class="badge">Difficult <input id="gDiff" type="number" class="border rounded px-2 py-1" value="10" min="0" style="width:80px"></span>
              <!--span class="badge">Auto distribute 50 question randomly(for testing purposes)</span-->
              <!--button id="autoDistributeBtn" ><i class="fas fa-shuffle mr-1"></i>Auto-Distribute</button-->
              <!--button id="addRowBtn" class="px-3 py-2 rounded border bg-gray-50 hover:bg-gray-100"><i class="fas fa-plus mr-1"></i>Add Row</button-->
            </div>
          </div>
        </div>

        <div id="rows" class="p-6 grid gap-4"></div>

        <div class="px-6 pb-6">
          <div id="tosWarn" class="text-sm text-red-600 my-2 hidden"></div>
          <div id="tosMatrixWrap"></div>
          <br/>
        </div>
      </div>

      <!-- Generate + Export -->
      <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center gap-2">
          <button id="btnGenerate" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-2 rounded-lg hover:from-blue-600 hover:to-blue-700 shadow">
            <i class="fas fa-wand-magic-sparkles mr-2"></i>Generate Exams
          </button>
          <button id="btnSaveDbTop" class="bg-white text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-100 border border-gray-200 shadow-sm" disabled title="Nothing to save yet">
            <i class="fas fa-save mr-2"></i>Save Exam
          </button>
          <button id="dlTosPng" type="button" class="bg-white text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-100 border border-gray-200 shadow-sm">Download TOS (PNG)</button>
        </div>

        <!-- Loader -->
        <div id="loaderBox" class="p-6 hidden">
          <div class="flex items-start gap-3 text-gray-700">
            <i class="fas fa-circle-notch spinner mt-1"></i>
            <div class="flex-1">
              <div id="loaderMain" class="font-medium">Preparing…</div>
              <div id="loaderSub" class="text-sm text-gray-500 mt-1">Please wait while we process your files.</div>
              <div class="mt-2 h-2 rounded-full bg-indigo-50 overflow-hidden">
                <div id="progressBar" class="h-full w-0" style="background:linear-gradient(90deg,#3b82f6,#60a5fa);transition:width .25s ease"></div>
              </div>
              <div id="progressText" class="text-xs text-gray-500 mt-1">0%</div>
            </div>
          </div>
        </div>

        <!-- Preview -->
       
        
        
        <!-- Preview (dual view: Paper / Text) -->
        <div class="p-6">
          <div class="flex items-center justify-between mb-3">
            <div class="text-sm text-gray-700 font-medium">Output Preview</div>
            <div class="inline-flex rounded-lg overflow-hidden border border-gray-200">
              <button id="viewPaperBtn"
                      type="button"
                      class="px-3 py-1.5 text-sm bg-white hover:bg-gray-50">
                Paper View
              </button>
              <button id="viewTextBtn"
                      type="button"
                      class="px-3 py-1.5 text-sm bg-gray-100 hover:bg-gray-200">
                Text View
              </button>
            </div>
          </div>
          
        
          <!-- Paper View -->
          <div id="paperPanel">
            <div id="sets" class="grid gap-6"></div>
          </div>
        
          <!-- Text View -->
          <div id="textPanel" class="hidden">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Compiled Output (copy/paste or edit here; this is what will be saved)
            </label>
            <textarea id="compiledText"
                      class="w-full border border-gray-300 rounded-lg p-3 font-mono text-sm"
                      rows="28"
                      placeholder="Generated exam will appear here..."></textarea>
            <div class="text-xs text-gray-500 mt-2">
              Format: Exam Set 1, Exam Set 2, ... then Answer Key Set 1, Answer Key Set 2, ...
            </div>
          </div>
        </div>


      </div>
    </main>
  </div>
  <div id="tosExportWrap" class="hidden"></div>

 
  <script> // TOPS
  
  /* ============ DOWNLOAD TOS ============= */
  
  /* ---------- TOS Export (clean table -> PNG) ---------- */



// Build a clean, print-friendly table (no inputs/buttons)
function buildTOStableHTML() {
  const { rows } = buildTOS(); // uses your existing function
  if (!rows.length) return '<div class="text-gray-500">No TOS rows.</div>';

  // live totals
  const sum = rows.reduce((a, d) => {
    a.r  += d.cells.r.length;
    a.u  += d.cells.u.length;
    a.ap += d.cells.ap.length;
    a.an += d.cells.an.length;
    a.cr += d.cells.cr.length;
    a.ev += d.cells.ev.length;
    a.days += d.days|0;
    a.items += d.total|0;
    return a;
  }, {r:0,u:0,ap:0,an:0,cr:0,ev:0,days:0,items:0});

  const grand = Math.max(1, sum.items);
  const perc = rows.map(r => (r.total/grand*100));
  // round + adjust last cell to hit ~100%
  const perc2 = perc.map(x => Math.round(x*100)/100);
  const deficit = Math.round((100 - perc2.reduce((a,b)=>a+b,0))*100)/100;
  if (Math.abs(deficit) >= 0.01) perc2[perc2.length-1] = Math.round((perc2[perc2.length-1]+deficit)*100)/100;

  // styles scoped to export only
  const styles = `
    <style>
      .tos-card{max-width:1200px;margin:0 auto;background:#fff;padding:18px;border:1px solid #e5e7eb;border-radius:14px}
      .tos-title{font:600 16px/1.2 system-ui,Segoe UI,Arial;margin-bottom:10px}
      .tos-table{width:100%;border-collapse:collapse;font:13px/1.4 system-ui,Segoe UI,Arial;color:#111}
      .tos-table th,.tos-table td{border:1px solid #e5e7eb;vertical-align:top;padding:8px}
      .tos-table th{background:#f8fafc;text-align:center;font-weight:600}
      .tos-num{white-space:nowrap;text-align:center}
      .tos-topic{min-width:220px}
      .tos-foot th{background:#f3f4f6}
      .muted{color:#6b7280}
      .sub{font-size:12px;color:#374151}
      /* better wrapping for lists of numbers */
      .nums{font-family:ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;font-size:12px;word-break:break-word}
    </style>
  `;

  const head = `
    <thead>
      <tr>
        <th rowspan="2" class="tos-topic">Topic</th>
        <th colspan="6">Bloom Levels (items)</th>
        <th rowspan="2">No. of items</th>
        <th rowspan="2">No. of days</th>
        <th rowspan="2">Percent in test</th>
      </tr>
      <tr>
        <th>Remember</th><th>Understand</th>
        <th>Apply</th><th>Analyze</th>
        <th>Create</th><th>Evaluate</th>
      </tr>
    </thead>
  `;

  const body = `
    <tbody>
      ${rows.map((d,i)=>`
        <tr>
          <td><div class="sub">${escapeHTML(d.topic||'')}</div></td>
          <td><div class="nums">${escapeHTML((d.cells.r||[]).join(', '))}</div><div class="tos-num muted">${d.cells.r.length}</div></td>
          <td><div class="nums">${escapeHTML((d.cells.u||[]).join(', '))}</div><div class="tos-num muted">${d.cells.u.length}</div></td>
          <td><div class="nums">${escapeHTML((d.cells.ap||[]).join(', '))}</div><div class="tos-num muted">${d.cells.ap.length}</div></td>
          <td><div class="nums">${escapeHTML((d.cells.an||[]).join(', '))}</div><div class="tos-num muted">${d.cells.an.length}</div></td>
          <td><div class="nums">${escapeHTML((d.cells.cr||[]).join(', '))}</div><div class="tos-num muted">${d.cells.cr.length}</div></td>
          <td><div class="nums">${escapeHTML((d.cells.ev||[]).join(', '))}</div><div class="tos-num muted">${d.cells.ev.length}</div></td>
          <td class="tos-num"><strong>${d.total}</strong></td>
          <td class="tos-num">${d.days|0}</td>
          <td class="tos-num">${(perc2[i]||0).toFixed(2)}%</td>
        </tr>
      `).join('')}
    </tbody>
  `;

  const foot = `
    <tfoot class="tos-foot">
      <tr>
        <th>Totals</th>
        <th class="tos-num">${sum.r}</th>
        <th class="tos-num">${sum.u}</th>
        <th class="tos-num">${sum.ap}</th>
        <th class="tos-num">${sum.an}</th>
        <th class="tos-num">${sum.cr}</th>
        <th class="tos-num">${sum.ev}</th>
        <th class="tos-num"><strong>${sum.items}</strong></th>
        <th class="tos-num">${sum.days}</th>
        <th class="tos-num"><strong>100.00%</strong></th>
      </tr>
    </tfoot>
  `;

  return `
    ${styles}
    <div class="tos-card" id="tosExportCard">
      <div class="tos-title">Table of Specifications (Export)</div>
      <table class="tos-table">
        ${head}${body}${foot}
      </table>
      <div class="muted" style="margin-top:6px">Note: counts under each Bloom level are shown below the item lists.</div>
    </div>
  `;
}

// Render the clean table into the hidden wrapper
function renderTOSExport() {
  const wrap = byId('tosExportWrap');
  if (!wrap) return null;
  wrap.classList.remove('hidden');
  wrap.innerHTML = buildTOStableHTML();
  return byId('tosExportCard');
}

// Download as PNG (via html2canvas already loaded on your page)
async function downloadTOStablePNG(filename = 'TOS-table.png') {

  const card = renderTOSExport();
  if (!card) { showAlert?.('err','Nothing to export.'); return; }

  // snapshot at 2x scale for crisp text
  const canvas = await html2canvas(card, { scale: 2, backgroundColor: '#ffffff', useCORS: true });
  const url = canvas.toDataURL('image/png');

  // re-hide the export DOM
  const wrap = byId('tosExportWrap'); if (wrap) wrap.classList.add('hidden');

  const a = document.createElement('a');
  a.href = url; a.download = filename;
  document.body.appendChild(a); a.click(); a.remove();
}


byId('dlTosPng')?.addEventListener('click', ()=> downloadTOStablePNG(`${(byId('examTitle')?.value||'Exam').trim()}-TOS.png`));

  
  
  /* =========================
   TOS-only data model (source of truth)
========================= */
const tosRows = []; // [{id, topic, days, files:[], figs:[], selected:Set<number>, cells:{r,u,ap,an,cr,ev}}]
const newId = () => 'tos_' + Math.random().toString(36).slice(2,9);
/*
function createEmptyTosRow() {
  return {
    id: newId(),
    topic: '',
    days: 1,
    files: [],
    figs: [],                 // [{src, name}]
    selected: new Set(),      // selected figure indexes
    cells: { r:[], u:[], ap:[], an:[], cr:[], ev:[] }
  };
}

function addTosRow(initial = {}) {
  const row = createEmptyTosRow();
  if (initial.topic) row.topic = initial.topic;
  if (Number.isFinite(initial.days)) row.days = initial.days|0;
  ['r','u','ap','an','cr','ev'].forEach(k=>{
    const v = initial[k]; if (v) row.cells[k] = parseNums(v);
  });
  tosRows.push(row);
  renderTOS();
}
*/

function redistributeFromCounts() {
  // Sum targets per Bloom across all rows
  const keys = ['r','u','ap','an','cr','ev'];
  const totals = { r:0,u:0,ap:0,an:0,cr:0,ev:0 };
  tosRows.forEach(row=> keys.forEach(k=> totals[k] += Math.max(0, row.counts[k]|0)));

  // allocate global numbers 1..N and give each Bloom its own consecutive range
  let cursor = 1;
  const pool = {};
  keys.forEach(k=>{
    const n = totals[k]|0;
    pool[k] = Array.from({length:n}, (_,i)=> cursor + i);
    cursor += n;
  });

  // clear old arrays
  tosRows.forEach(r=> keys.forEach(k=> r.cells[k] = []));

  // for each Bloom, slice per-row counts sequentially
  keys.forEach(k=>{
    let p = 0; const arr = pool[k];
    tosRows.forEach(row=>{
      const want = Math.max(0, row.counts[k]|0);
      const take = arr.slice(p, p + want);
      row.cells[k] = take;
      p += want;
    });
  });
}

function createEmptyTosRow() {
  return {
    id: newId(),
    topic: '',
    days: 1,
    files: [],
    figs: [],
    selected: new Set(),
    // arrays used by the rest of the pipeline
    cells: { r:[], u:[], ap:[], an:[], cr:[], ev:[] },
    // NEW: totals user types (per Bloom)
    counts: { r:0, u:0, ap:0, an:0, cr:0, ev:0 }
  };
}

function addTosRow(initial = {}) {
  const row = createEmptyTosRow();
  if (initial.topic) row.topic = initial.topic;
  if (Number.isFinite(initial.days)) row.days = initial.days|0;

  // accept legacy numbers or initial counts
  ['r','u','ap','an','cr','ev'].forEach(k=>{
    if (initial[k]) row.cells[k] = parseNums(initial[k]);
    if (Number.isFinite(initial[k+'_count'])) row.counts[k] = Math.max(0, initial[k+'_count']|0);
  });

  // if numbers were provided, sync counts from them
  ['r','u','ap','an','cr','ev'].forEach(k=>{
    if (!row.counts[k]) row.counts[k] = row.cells[k].length|0;
  });

  tosRows.push(row);
  renderTOS();
}



function removeTosRowByIndex(idx){
  if (idx>=0 && idx<tosRows.length){ tosRows.splice(idx,1); renderTOS(); }
}

/* Utility for updating numeric-list cells from string */
function setCellFromString(row, key, str){
  row.cells[key] = parseNums(str);
}

/* Convenience accessors used by the rest of your pipeline */
function countTotalNumbersFromTosRows(){
  const all = [];
  tosRows.forEach(r=>{
    all.push(...r.cells.r, ...r.cells.u, ...r.cells.ap, ...r.cells.an, ...r.cells.cr, ...r.cells.ev);
  });
  return Array.from(new Set(all)).length;
}

  
  
  
  
  
  
  
  
  
  
/* -----------------------------------------------------------
   Tiny DOM fallbacks (kept minimal & non-breaking)
----------------------------------------------------------- */
window.$  = window.$  || (sel => document.querySelector(sel));
window.$$ = window.$$ || (sel => Array.from(document.querySelectorAll(sel)));
window.escapeHTML = window.escapeHTML || function (s) {
  const d = document.createElement('div');
  d.textContent = String(s ?? '');
  return d.innerHTML;
};
function byId(id){ return document.getElementById(id); }
const rowsEl = () => byId('rows');
const uid = () => 'row_' + Math.random().toString(36).slice(2,8);
const shuffle = arr => { for(let i=arr.length-1;i>0;i--){ const j=Math.floor(Math.random()*(i+1)); [arr[i],arr[j]]=[arr[j],arr[i]]; } return arr; };
const parseNums = s => !s ? [] : Array.from(new Set(String(s).split(/[, ]+/).map(x=>+x).filter(n=>Number.isInteger(n)&&n>0))).sort((a,b)=>a-b);

/* ================= UI helpers ================= */
function showAlert(type, text) {
  const box = $('#alertBox'); if (!box) { console.warn(text); return; }
  box.className = 'fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-opacity duration-500 opacity-100 ' +
    (type === 'ok' ? 'bg-green-50 border border-green-200 text-green-700' : 'bg-red-50 border border-red-200 text-red-700');
  box.textContent = text; box.classList.remove('hidden'); box.style.opacity = '1';
  clearTimeout(box._t); box._t = setTimeout(() => { box.style.opacity = '0'; setTimeout(() => box.classList.add('hidden'), 500); }, 3500);
}
function setLoader(main, sub){ $('#loaderMain') && ($('#loaderMain').textContent=main); $('#loaderSub') && ($('#loaderSub').textContent=sub||''); $('#loaderBox') && $('#loaderBox').classList.remove('hidden'); }
function hideLoader(){ $('#loaderBox') && $('#loaderBox').classList.add('hidden'); }
function setProgress(p, label){
  const pct=Math.max(0,Math.min(100,Math.round(p)));
  $('#progressBar') && ($('#progressBar').style.width=pct+'%');
  $('#progressText') && ($('#progressText').textContent=(label?label+' - ':'')+pct+'%');
}

/* ============== Profile + sidebar bindings ============== */
document.addEventListener('DOMContentLoaded', () => {
  const u = (parseJwt(localStorage.getItem(TOKEN_KEY)||'')?.user) || payload.user || payload.data || payload;
  const cache = JSON.parse(localStorage.getItem(CACHE_KEY)||'{}');
  const name = u?.name || u?.username || u?.email || cache.name || 'User';
  const pic  = u?.profile_picture || u?.picture || u?.avatar || cache.profile_picture || '/images/default-avatar.png';
  if (byId('displayName')) byId('displayName').textContent = name;
  if (byId('profilePic')) byId('profilePic').src = pic;
  byId('logoutBtn')?.addEventListener('click', ()=>{ localStorage.removeItem(TOKEN_KEY); localStorage.removeItem(CACHE_KEY); location.replace('/login'); });
});

/* --- Dual view toggle (Paper/Text) --- */
(function(){
  const btnPaper = document.getElementById('viewPaperBtn');
  const btnText  = document.getElementById('viewTextBtn');
  const panelPaper = document.getElementById('paperPanel');
  const panelText  = document.getElementById('textPanel');
  if (!btnPaper || !btnText || !panelPaper || !panelText) return;
  function setActive(which){
    if (which === 'paper'){
      panelPaper.classList.remove('hidden');
      panelText.classList.add('hidden');
      btnPaper.classList.add('bg-white');     btnPaper.classList.remove('bg-gray-100');
      btnText.classList.add('bg-gray-100');   btnText.classList.remove('bg-white');
    } else {
      panelPaper.classList.add('hidden');
      panelText.classList.remove('hidden');
      btnPaper.classList.add('bg-gray-100');  btnPaper.classList.remove('bg-white');
      btnText.classList.add('bg-white');      btnText.classList.remove('bg-gray-100');
    }
  }
  btnPaper.addEventListener('click', ()=> setActive('paper'));
  btnText.addEventListener('click',  ()=> setActive('text'));
  setActive('paper');
})();

/* ================= File -> Markdown helpers ================= */
const readAsArrayBuffer = f => new Promise((res,rej)=>{ const r=new FileReader(); r.onload=()=>res(r.result); r.onerror=rej; r.readAsArrayBuffer(f); });
const readAsText = f => new Promise((res,rej)=>{ const r=new FileReader(); r.onload=()=>res(String(r.result||'')); r.onerror=rej; r.readAsText(f); });
function _cleanLines(s=""){ return s.replace(/[ \t]+\n/g,"\n").replace(/\n{3,}/g,"\n\n").trim(); }
function stripTags(x=""){ return String(x).replace(/<\/?[^>]+>/g, ""); }
function tableHTMLToMD(inner=""){
  const rows=[]; inner.replace(/<tr[^>]*>([\s\S]*?)<\/tr>/gi,(_,rowHTML)=>{ const cells=[]; rowHTML.replace(/<(td|th)[^>]*>([\s\S]*?)<\/\1>/gi,(_,tag,cell)=>{cells.push(stripTags(cell).trim()); return "";}); rows.push(cells); return "";});
  if(!rows.length) return "";
  const header=rows[0]; const sep=header.map(()=>("---")); const lines=[`| ${header.join(" | ")} |`,`| ${sep.join(" | ")} |`];
  for(let i=1;i<rows.length;i++){ const row=rows[i]; if(!row.length) continue; while(row.length<header.length) row.push(""); lines.push(`| ${row.join(" | ")} |`); }
  return "\n"+lines.join("\n")+"\n";
}
function htmlToMarkdown(html = "") {
  if (!html) return "";
  let s = String(html).replace(/\r/g, "").replace(/<\/(p|div|h[1-6]|li|ul|ol|blockquote|pre|table|tr)>/gi, "</$1>\n");
  s = s.replace(/<pre[^>]*><code[^>]*>([\s\S]*?)<\/code><\/pre>/gi, (_, code)=>"\n```\n"+code+"\n```\n");
  s = s.replace(/<code[^>]*>([\s\S]*?)<\/code>/gi, (_, code)=>"`"+code.replace(/`/g,"\\`")+"`");
  for (let n=6;n>=1;n--){ const rx=new RegExp(`<h${n}[^>]*>([\\s\\S]*?)<\\/h${n}>`,"gi"); s=s.replace(rx,(_,t)=>"\n"+"#".repeat(n)+" "+stripTags(t).trim()+"\n"); }
  s = s.replace(/<(strong|b)[^>]*>([\s\S]*?)<\/\1>/gi,(_,__,t)=>`**${stripTags(t).trim()}**`);
  s = s.replace(/<(em|i)[^>]*>([\s\S]*?)<\/\1>/gi,(_,__,t)=>`*${stripTags(t).trim()}*`);
  s = s.replace(/<a[^>]*href="([^"]+)"[^>]*>([\s\S]*?)<\/a>/gi,(_,href,txt)=>`[${stripTags(txt).trim()}](${href})`);
  s = s.replace(/<img[^>]*alt="([^"]*)"[^>]*src="([^"]+)"[^>]*>/gi,(_,alt,src)=>`![${alt}](${src})`);
  s = s.replace(/<li[^>]*>([\s\S]*?)<\/li>/gi,(_,t)=>`• ${stripTags(t).trim()}\n`);
  s = s.replace(/<\/ul>/gi,"\n"); s=s.replace(/<ul[^>]*>/gi,"\n");
  s = s.replace(/<\/ol>/gi,"\n"); s=s.replace(/<ol[^>]*>/gi,"\n");
  s = s.replace(/<blockquote[^>]*>([\s\S]*?)<\/blockquote>/gi,(_,t)=>"\n> "+stripTags(t).trim().replace(/\n/g,"\n> ")+"\n");
  s = s.replace(/<br\s*\/?>/gi,"\n");
  s = s.replace(/<(p|div)[^>]*>([\s\S]*?)<\/\1>/gi,(_,__,t)=>"\n"+stripTags(t).trim()+"\n");
  s = s.replace(/<table[^>]*>([\s\S]*?)<\/table>/gi,(_,inner)=>tableHTMLToMD(inner));
  s = stripTags(s);
  return _cleanLines(s);
}
async function docxToMarkdown(file){
  if (typeof mammoth === 'undefined') { return `# ${file.name}\n\n(Install mammoth.js to parse DOCX.)`; }
  const ab=await readAsArrayBuffer(file);
  const htmlRes=await mammoth.convertToHtml({ arrayBuffer:ab });
  return htmlToMarkdown(htmlRes.value||"");
}
async function pdfToMarkdown(file){
  if (typeof pdfjsLib === 'undefined') { return `# ${file.name}\n\n(Install pdf.js to parse PDF.)`; }
  const ab=await readAsArrayBuffer(file);
  const pdf=await pdfjsLib.getDocument({ data: ab }).promise;
  const out=[]; for(let p=1;p<=pdf.numPages;p++){ const page=await pdf.getPage(p); const content=await page.getTextContent(); const text=content.items.map(it=>(it.str||"").trim()).filter(Boolean).join(" ").replace(/\s{2,}/g," ").trim(); if(text) out.push(text); }
  return _cleanLines(out.join("\n\n"));
}
async function pptxToMarkdown(file){
  if (typeof JSZip === 'undefined') { return `# ${file.name}\n\n(Install JSZip to parse PPTX.)`; }
  const ab=await readAsArrayBuffer(file); const zip=await JSZip.loadAsync(ab);
  const slideFiles=Object.keys(zip.files).filter(p=>/^ppt\/slides\/slide\d+\.xml$/i.test(p)).sort((a,b)=>parseInt(a.match(/slide(\d+)\.xml/i)[1])-parseInt(b.match(/slide(\d+)\.xml/i)[1]));
  const parts=[];
  for(let i=0;i<slideFiles.length;i++){
    const xml=await zip.files[slideFiles[i]].async("text");
    const runs=Array.from(xml.matchAll(/<a:t>([\s\S]*?)<\/a:t>/g)).map(m=>m[1]);
    const textChunks=runs.map(t=>t.replace(/&lt;|&gt;|&amp;/g,m=>({'&lt;':'<','&gt;':'>','&':'&'}[m])).trim()).filter(Boolean);
    if(!textChunks.length) continue; parts.push(textChunks.join(". "));
  }
  return _cleanLines(parts.join("\n\n")) || "";
}
async function sheetToMarkdown(file,isCSV=false){
  if (typeof XLSX === 'undefined') { return `# ${file.name}\n\n(Install SheetJS to parse ${isCSV?'CSV':'XLSX'}.)`; }
  let wb; if(isCSV){ const text=await readAsText(file); wb=XLSX.read(text,{type:"string"});} else { const ab=await readAsArrayBuffer(file); wb=XLSX.read(ab,{type:"array"}); }
  const sheet=wb.Sheets[wb.SheetNames[0]]; const rows=XLSX.utils.sheet_to_json(sheet,{header:1,raw:true}); if(!rows.length) return "";
  const strRows=rows.map(r=>(r||[]).map(c=>(c==null?"":String(c))));
  const header=strRows[0].length?strRows[0]:(strRows.find(r=>r.length)||[]); const sep=header.map(()=>("---")); const lines=[`| ${header.join(" | ")} |`,`| ${sep.join(" | ")} |`];
  for(let i=1;i<strRows.length;i++){ const row=strRows[i]; if(!row.length) continue; const cells=row.slice(0,header.length); while(cells.length<header.length) cells.push(""); lines.push(`| ${cells.join(" | ")} |`); }
  return _cleanLines(lines.join("\n"));
}
async function htmlFileToMarkdown(file){ const html=await readAsText(file); let body=""; try{ const doc=new DOMParser().parseFromString(html,"text/html"); body=doc && doc.body ? doc.body.innerHTML : html; }catch{ body=html; } return htmlToMarkdown(body||html); }
async function mdToMarkdown(file){ return readAsText(file); }
async function convertFileToMarkdown(file){
  const ext=(file.name.split('.').pop()||'').toLowerCase();
  if(ext==='md') return mdToMarkdown(file);
  if(ext==='txt') return readAsText(file);
  if(ext==='html'||ext==='htm') return htmlFileToMarkdown(file);
  if(ext==='docx') return docxToMarkdown(file);
  if(ext==='pdf') return pdfToMarkdown(file);
  if(ext==='pptx') return pptxToMarkdown(file);
  if(ext==='xlsx') return sheetToMarkdown(file,false);
  if(ext==='csv') return sheetToMarkdown(file,true);
  throw new Error('Unsupported file type: .'+ext);
}

/* ================= Minimal figure extraction (kept) ================= */
async function extractFigures(files){
  const figs=[];
  for(const f of files){
    const ext=(f.name.split('.').pop()||'').toLowerCase();
    if((ext==='docx'||ext==='pptx') && typeof JSZip!=='undefined'){
      const ab=await readAsArrayBuffer(f); const zip=await JSZip.loadAsync(ab);
      const media=Object.keys(zip.files).filter(p=>/\/media\/.+\.(png|jpe?g|gif|webp|bmp)$/i.test(p));
      for(const p of media){ const blob=await zip.files[p].async('blob'); const url=URL.createObjectURL(blob); figs.push({src:url, name:p.split('/').pop()}); }
    } else if (ext==='html'||ext==='htm'){
      const html=await readAsText(f); const doc=new DOMParser().parseFromString(html,'text/html');
      doc.querySelectorAll('img[src]').forEach(img=> figs.push({src:img.getAttribute('src'), name:img.getAttribute('alt')||'image'}));
    } else if (ext==='md'||ext==='txt'){
      const txt=await readAsText(f); const re=/!\[([^\]]*)\]\(([^)]+)\)/g; let m; while((m=re.exec(txt))){ figs.push({src:m[2], name:m[1]||'image'}); }
    }
  }
  return figs;
}

/* ================= Rows (TOS + Bloom) ================= */
const rowState = {}; // id -> {files:[], figs:[], selectedFigIdx:Set, topic:'', days:1}


function makeRowCard(id, initial={}){
  const card=document.createElement('div'); card.className='border rounded-xl p-4'; card.dataset.rowId=id;
  card.innerHTML=`
    <div class="grid md:grid-cols-4 gap-4">
      <!-- LEFT: Topic + days + upload + figures -->
      <div class="md:col-span-1">
        <label class="text-sm font-medium">Most Essential Learning Competency (Topic)</label>
        <input type="text" class="topicInput border rounded px-2 py-2 w-full mb-2" placeholder="e.g., Identify basic hand movements..." value="${escapeHTML(initial.topic||'')}">

        <label class="text-sm font-medium">No. of days</label>
        <input type="number" class="daysInput border rounded px-2 py-2 w-full mb-2" min="0" value="${initial.days!=null?initial.days:1}">

        <label class="text-sm font-medium">Attach Materials (per row)</label>
        <center>
        <div class="container" data-dropzone>
          <div class="folder">
            <div class="front-side">
              <div class="tip"></div>
              <div class="cover"></div>
            </div>
            <div class="back-side cover"></div>
          </div>
          <label class="custom-file-upload">
            <input type="file" class="title hidden fileInput openPicker" multiple accept=".pdf,.docx,.pptx,.xlsx,.csv,.txt,.html,.htm,.md"/>
            Upload Materials
          </label>
        </div>
        </center>

        <div class="flex items-center gap-2 mt-2">
          <div class="fileBadges flex flex-wrap gap-2"></div>
        </div>

        <div class="mt-3">
          <div class="flex items-center justify-between">
            <label class="text-sm font-medium">Figures (choose manually)</label>
            <div class="flex items-center gap-2">
              <button type="button" class="text-xs underline text-blue-600 refreshFigs">Rescan</button>
              <!-- NEW: modal launcher beside upload -->
              <button type="button" class="text-xs underline text-indigo-600 chooseFigsBtn">Choose images…</button>
            </div>
          </div>
          <details>
            <summary>Figures(Show/Hide):</summary>
            <div class="figWrap grid grid-cols-3 gap-2 mt-2"></div>
          </details>
        </div>

        <button type="button" class="text-red-600 text-sm hover:underline self-start rmBtn mt-3">Remove Row</button>
      </div>

      <!-- RIGHT: Bloom areas (always visible) -->
      <div class="md:col-span-3">
        <div class="grid md:grid-cols-3 gap-3">
          <div>
            <div class="font-medium mb-1">Easy</div>
            <label class="small">Remembering</label>
            <textarea class="r border rounded w-full p-2 small" rows="2" placeholder="e.g., 1,2,3"></textarea>
            <label class="small mt-2 block">Understanding</label>
            <textarea class="u border rounded w-full p-2 small" rows="2"></textarea>
          </div>
          <div>
            <div class="font-medium mb-1">Average</div>
            <label class="small">Applying</label>
            <textarea class="ap border rounded w-full p-2 small" rows="2"></textarea>
            <label class="small mt-2 block">Analyzing</label>
            <textarea class="an border rounded w-full p-2 small" rows="2"></textarea>
          </div>
          <div>
            <div class="font-medium mb-1">Difficult</div>
            <label class="small">Creating</label>
            <textarea class="cr border rounded w-full p-2 small" rows="2"></textarea>
            <label class="small mt-2 block">Evaluating</label>
            <textarea class="ev border rounded w-full p-2 small" rows="2"></textarea>
          </div>
        </div>
      </div>
    </div>
  `;
  return card;
}


function bindRowCard(card){
  const id=card.dataset.rowId;
  const dz=card.querySelector('[data-dropzone]'); const picker=card.querySelector('.openPicker'); const fileInput=card.querySelector('.fileInput');
  const badges=card.querySelector('.fileBadges'); const figWrap=card.querySelector('.figWrap');
  const renderBadges=()=>{ badges.innerHTML=''; (rowState[id].files||[]).forEach((f,i)=>{ const span=document.createElement('span'); span.className='fileBadge'; span.innerHTML=`<span class="small">${escapeHTML(f.name)}</span><button title="Remove" type="button">&times;</button>`; span.querySelector('button').onclick=()=>{ rowState[id].files.splice(i,1); renderBadges(); scanFigs(); }; badges.appendChild(span); }); };
  const addFiles=(files)=>{ if(!files?.length) return; rowState[id].files.push(...Array.from(files)); renderBadges(); scanFigs(); };
  picker && (picker.onclick=()=>fileInput.click()); fileInput && (fileInput.onchange=e=>addFiles(e.target.files));
  if (dz){
    ['dragover','dragenter'].forEach(ev=>dz.addEventListener(ev,e=>{e.preventDefault(); dz.classList.add('border-blue-400');}));
    ['dragleave','drop'].forEach(ev=>dz.addEventListener(ev,e=>{e.preventDefault(); dz.classList.remove('border-blue-400');}));
    dz.addEventListener('drop',e=>addFiles(e.dataTransfer.files));
  }

  async function scanFigs(){
    figWrap.innerHTML='<div class="text-xs text-gray-500">Scanning…</div>';
    try{
      const figs=await extractFigures(rowState[id].files||[]);
      rowState[id].figs = figs;
      if(!rowState[id].selectedFigIdx) rowState[id].selectedFigIdx = new Set();
      figWrap.innerHTML = '';
      figs.forEach((fg,idx)=>{
        const box=document.createElement('label'); box.className='flex flex-col items-center text-xs';
        box.innerHTML = `<img class="fg-thumb" src="${fg.src}" alt=""><input type="checkbox" class="fgChk"><div class="truncate w-full">${escapeHTML(fg.name||('Figure '+(idx+1)))}</div>`;
        const chk=box.querySelector('.fgChk'); chk.checked=rowState[id].selectedFigIdx.has(idx);
        chk.onchange=()=>{ if(chk.checked) rowState[id].selectedFigIdx.add(idx); else rowState[id].selectedFigIdx.delete(idx); };
        figWrap.appendChild(box);
      });
      if(!figs.length) figWrap.innerHTML='<div class="text-xs text-gray-500">No figures detected.</div>';
    }catch{ figWrap.innerHTML='<div class="text-xs text-red-600">Figure scan failed.</div>'; }
  }
  card.querySelector('.refreshFigs')?.addEventListener('click', scanFigs);

  // card.querySelectorAll('textarea, .topicInput, .daysInput').forEach(el=> el.addEventListener('input', renderTOS)); 0970
  
  card.querySelectorAll('textarea, .topicInput, .daysInput').forEach(el=>{
  const apply = ()=> {
    const id = card.dataset.rowId;
    const topicEl = card.querySelector('.topicInput');
    const daysEl  = card.querySelector('.daysInput');
    if (topicEl) rowState[id].topic = topicEl.value;
    if (daysEl)  rowState[id].days  = Math.max(0, parseInt(daysEl.value||'0',10));
  };
  el.addEventListener('input', apply);            // no re-render on every key
  el.addEventListener('change', ()=>{ apply(); renderTOS(); });
  el.addEventListener('blur',   ()=>{ apply(); renderTOS(); });
});

  
  card.querySelector('.rmBtn')?.addEventListener('click', ()=>{ delete rowState[id]; card.remove(); renderTOS(); });

  renderBadges();
}
function addRow(initial = {}) {
  const root = rowsEl(); if (!root) { console.warn('#rows not found'); return; }
  const id=uid(); rowState[id] = { files:[], figs:[], selectedFigIdx:new Set(), topic: initial.topic||'', days: initial.days!=null?initial.days:1 };
  const card = makeRowCard(id, initial); root.appendChild(card); bindRowCard(card); renderTOS();
}

/* ===== Global Distribute from totals ===== */
function splitEvenly(arr){ const half=Math.floor(arr.length/2); return [arr.slice(0, half + (arr.length%2?1:0)), arr.slice(half + (arr.length%2?1:0))]; }
function clearAllCells(){ $$('#rows textarea').forEach(t=>t.value=''); }
function roundRobinAssign(nums, selector){
    if (!tosRows.length) return;
  const cards=tosRows.length; 
// if(!cards.length) return;
  let i=0; nums.forEach(n=>{ const c=cards[i%cards.length]; const ta=c.querySelector(selector); const cur=parseNums(ta.value); cur.push(n); cur.sort((a,b)=>a-b); ta.value=cur.join(', '); i++; });
}


function autoDistribute(){
  const E=+byId('gEasy')?.value||0, A=+byId('gAvg')?.value||0, D=+byId('gDiff')?.value||0;
  const N=E+A+D;
  if(N<=0) return showAlert('err','Totals must be > 0.');
  if(!tosRows.length) return showAlert('err','Add at least one row first.');

  const nums = shuffle(Array.from({length:N},(_,i)=>i+1));
  const easy = nums.slice(0,E), avg = nums.slice(E,E+A), diff = nums.slice(E+A);
  const splitEven = arr => { const half=Math.floor(arr.length/2); const a=arr.slice(0, half + (arr.length%2?1:0)); const b=arr.slice(half + (arr.length%2?1:0)); return [a,b]; };
  const [R,U]  = splitEven(easy);
  const [AP,AN]= splitEven(avg);
  const [CR,EV]= splitEven(diff);

  // round-robin push into tosRows
  const assign = (nums, key)=>{
    let i=0; nums.forEach(n=>{
      const row = tosRows[i % tosRows.length];
      row.cells[key].push(n);
      row.cells[key] = Array.from(new Set(row.cells[key])).sort((a,b)=>a-b);
      i++;
    });
  };
  // clear old numbers
  tosRows.forEach(r=>{ r.cells.r=[]; r.cells.u=[]; r.cells.ap=[]; r.cells.an=[]; r.cells.cr=[]; r.cells.ev=[]; });

  assign(R,'r');  assign(U,'u');
  assign(AP,'ap');assign(AN,'an');
  assign(CR,'cr');assign(EV,'ev');

  renderTOS();
}


/* ===== Build TOS and render ===== */

function buildTOS(){
  const tableWrap = byId('tosMatrixWrap');

  const rows = tosRows.map(r=>{
    const uniq = new Set([...r.cells.r, ...r.cells.u, ...r.cells.ap, ...r.cells.an, ...r.cells.cr, ...r.cells.ev]);
    return {
      topic: r.topic || '',
      days:  Number.isFinite(r.days) ? r.days|0 : 0,
      cells: {
        r: r.cells.r.slice(),
        u: r.cells.u.slice(),
        ap: r.cells.ap.slice(),
        an: r.cells.an.slice(),
        cr: r.cells.cr.slice(),
        ev: r.cells.ev.slice(),
      },
      total: uniq.size
    };
  });

  // Build number->Bloom map (for quotas/difficulty)
  const map = {};
  const push=(arr,tag)=>arr.forEach(n=>map[n]=tag);
  rows.forEach(d=>{
    push(d.cells.r,'remember'); push(d.cells.u,'understand');
    push(d.cells.ap,'apply');   push(d.cells.an,'analyze');
    push(d.cells.cr,'create');  push(d.cells.ev,'evaluate');
  });

  return { rows, numMap: map, tableWrap };
}

function renderTOS(){
  const { rows, tableWrap } = buildTOS();
  if(!tableWrap) return;

  if(!rows.length){
    tableWrap.innerHTML='';
    byId('tosWarn') && byId('tosWarn').classList.add('hidden');
    return;
  }

  // live totals
  let sum={r:0,u:0,ap:0,an:0,cr:0,ev:0, days:0, items:0};
  rows.forEach(d=>{
    sum.r+=d.cells.r.length; sum.u+=d.cells.u.length;
    sum.ap+=d.cells.ap.length; sum.an+=d.cells.an.length;
    sum.cr+=d.cells.cr.length; sum.ev+=d.cells.ev.length;
    sum.days+=d.days; sum.items+=d.total;
  });

  const grand = sum.items || 0;
  let percents = rows.map(r=> grand? (r.total/grand*100):0);
  let round2 = percents.map(x=> Math.round(x*100)/100);
  const deficit = Math.round((100 - round2.reduce((a,b)=>a+b,0))*100)/100;
  if(rows.length && Math.abs(deficit) >= 0.01){
    round2[round2.length-1] = Math.round((round2[round2.length-1]+deficit)*100)/100;
  }

  const easyTotal = sum.r + sum.u;
  const avgTotal  = sum.ap + sum.an;
  const diffTotal = sum.cr + sum.ev;

  const warn = byId('tosWarn');
  if(warn){
    if(grand===0){
      warn.textContent='No items yet. Type numbers or use Auto-Distribute.';
      warn.classList.remove('hidden');
    } else {
      const s = round2.reduce((a,b)=>a+b,0);
      const ok = Math.abs(s-100)<=0.05;
      warn.textContent = ok?'':'Warning: Percent total is not exactly 100% (shown '+s.toFixed(2)+'%).';
      warn.classList.toggle('hidden', ok);
    }
  }

  const head = `
    <thead>
      <tr>
        <th rowspan="2">Topic</th>
        <th>Module</th>
        <th>Remember</th><th>Understand</th>
        <th>Apply</th><th>Analyze</th>
        <th>Create</th><th>Evaluate</th>
        <th rowspan="1">No. of items</th>
        <th>No. of days</th>
        <th rowspan="1">Percent in test</th>
        <th></th>
      </tr>
    </thead>
  `;

  const body = `
    <tbody>
      ${rows.map((d,i)=>`
        <tr data-row="${i}">
          <td><input class="tosCell border rounded px-2 py-1 w-full" data-field="topic" value="${escapeHTML(d.topic)}"></td>
          <td>
            <div class="flex items-center gap-2">
              <input id="file-input"  type="file" class="tosFileInput" multiple accept=".pdf,.docx,.pptx,.xlsx,.csv,.txt,.html,.htm,.md"/>
              <!--button id="figureBtn" type="button" class="figBtn text-xs underline text-blue-600">Pick Figures</button-->
            </div>
            <div style="font-size: 8px; max-width: 140px; overflow:hidden" class="tosFileList small mt-1 text-gray-600"> ${((tosRows[i]?.files||[]).map(f=>escapeHTML(f.name)).join(' • '))} </div>
          </td>
          <!--td class="text-center"><input style="width:70px" class="tosCell border rounded px-2 py-1 w-28 text-center" data-field="r"  value="${(d.cells.r||[]).join(', ')}"></td>
          <td class="text-center"><input style="width:70px" class="tosCell border rounded px-2 py-1 w-28 text-center" data-field="u"  value="${(d.cells.u||[]).join(', ')}"></td>
          <td class="text-center"><input style="width:70px" class="tosCell border rounded px-2 py-1 w-28 text-center" data-field="ap" value="${(d.cells.ap||[]).join(', ')}"></td>
          <td class="text-center"><input style="width:70px" class="tosCell border rounded px-2 py-1 w-28 text-center" data-field="an" value="${(d.cells.an||[]).join(', ')}"></td>
          <td class="text-center"><input style="width:70px" class="tosCell border rounded px-2 py-1 w-28 text-center" data-field="cr" value="${(d.cells.cr||[]).join(', ')}"></td>
          <td class="text-center"><input style="width:70px" class="tosCell border rounded px-2 py-1 w-28 text-center" data-field="ev" value="${(d.cells.ev||[]).join(', ')}"></td-->
          
          <td class="text-center">
  <input style="width:70px" type="number" min="0"
         class="tosCount border rounded px-2 py-1 w-28 text-center"
         data-field="r" value="${d.cells.r.length}">
</td>
<td class="text-center">
  <input style="width:70px" type="number" min="0"
         class="tosCount border rounded px-2 py-1 w-28 text-center"
         data-field="u" value="${d.cells.u.length}">
</td>
<td class="text-center">
  <input style="width:70px" type="number" min="0"
         class="tosCount border rounded px-2 py-1 w-28 text-center"
         data-field="ap" value="${d.cells.ap.length}">
</td>
<td class="text-center">
  <input style="width:70px" type="number" min="0"
         class="tosCount border rounded px-2 py-1 w-28 text-center"
         data-field="an" value="${d.cells.an.length}">
</td>
<td class="text-center">
  <input style="width:70px" type="number" min="0"
         class="tosCount border rounded px-2 py-1 w-28 text-center"
         data-field="cr" value="${d.cells.cr.length}">
</td>
<td class="text-center">
  <input style="width:70px" type="number" min="0"
         class="tosCount border rounded px-2 py-1 w-28 text-center"
         data-field="ev" value="${d.cells.ev.length}">
</td>

          
          <td class="text-center"><strong>${d.total}</strong></td>
          <td class="text-center"><input style="width:70px" class="tosCell border rounded px-2 py-1 w-20 text-center" data-field="days" type="number" min="0" value="${d.days}"></td>
          <td class="text-center">${(round2[i]||0).toFixed(2)}</td>
          <td class="text-right"><button type="button" id="removeBtn" class="rmRow text-xs text-red-600">Remove</button></td>
        </tr>
      `).join('')}
    </tbody>
  `;

  const foot = `
    <tfoot>
      <tr>
        <th><button id="addRowBtn" type="button" class="text-xs underline text-blue-600">Add Row</button></th></th>
        <th>Totals</th>
        <th class="text-center">${sum.r}</th>
        <th class="text-center">${sum.u}</th>
        <th class="text-center">${sum.ap}</th>
        <th class="text-center">${sum.an}</th>
        <th class="text-center">${sum.cr}</th>
        <th class="text-center">${sum.ev}</th>
        <th class="text-center"><strong>${sum.items}</strong></th>
        <th class="text-center">${sum.days}</th>
        <th class="text-center"><strong>${(round2.reduce((a,b)=>a+b,0)).toFixed(2)}</strong></th>
        <th></th><th></th>
      </tr>
      
    </tfoot>
  `;

  tableWrap.innerHTML = `
    <div class="border rounded-xl p-4">
      <div class="font-semibold mb-2">Item Placement - Table of Specifications (Editable)</div>
      <div class="overflow-x-auto"><table class="tos">${head}${body}${foot}</table></div>
    </div>
  `;


// NEW: when a count changes, update targets -> redistribute -> re-render
tableWrap.querySelectorAll('input.tosCount').forEach(inp=>{
  const apply = ()=>{
    const idx   = parseInt(inp.closest('tr').dataset.row,10);
    const row   = tosRows[idx]; if(!row) return;
    const field = inp.dataset.field;  // 'r','u','ap','an','cr','ev'
    const val   = Math.max(0, parseInt(inp.value||'0',10) || 0);
    // ensure counts object exists
    row.counts = row.counts || { r:0,u:0,ap:0,an:0,cr:0,ev:0 };
    row.counts[field] = val;
  };
  inp.addEventListener('input', apply);                 // don’t re-render every keystroke
  inp.addEventListener('change', ()=>{ apply(); redistributeFromCounts(); renderTOS(); });
  inp.addEventListener('blur',   ()=>{ apply(); redistributeFromCounts(); renderTOS(); });
});



tableWrap.querySelectorAll('input.tosCell').forEach(inp=>{
  const updateModel = ()=>{
    const idx = parseInt(inp.closest('tr').dataset.row,10);
    const row = tosRows[idx]; if(!row) return;
    const field = inp.dataset.field;
    if (field === 'topic')        row.topic = inp.value;
    else if (field === 'days')    row.days  = Math.max(0, parseInt(inp.value||'0',10) || 0);
    else                          setCellFromString(row, field, inp.value);
  };
  inp.addEventListener('input',  updateModel);                   // no re-render
  inp.addEventListener('change', ()=>{ updateModel(); renderTOS(); });
  inp.addEventListener('blur',   ()=>{ updateModel(); renderTOS(); });
});

  // remove row
  tableWrap.querySelectorAll('.rmRow').forEach(btn=>{
    btn.addEventListener('click', ()=>{
      const idx = parseInt(btn.closest('tr').dataset.row,10);
      removeTosRowByIndex(idx);
    });
  });

  // add row
  byId('addRowBtn')?.addEventListener('click', ()=> addTosRow());

  // files → tosRows[idx].files, then scan figures
  tableWrap.querySelectorAll('input.tosFileInput').forEach((fi)=>{
    fi.addEventListener('change', async (e)=>{
      const idx = parseInt(fi.closest('tr').dataset.row,10);
      const row = tosRows[idx]; if(!row) return;
      const add = Array.from(e.target.files||[]);
      row.files.push(...add);

      // show names
      const listEl = fi.closest('td').querySelector('.tosFileList');
      if (listEl) listEl.textContent = row.files.map(f=>f.name).join(' • ');

      // rescan figures for this row
      try{
        row.figs = await extractFigures(add);
      }catch(err){
        console.warn('Figure scan failed:', err);
      }
      // keep selection set as-is; user picks via modal
    });
  });

  // open figure picker modal
  tableWrap.querySelectorAll('.figBtn').forEach(btn=>{
    btn.addEventListener('click', ()=>{
      const idx = parseInt(btn.closest('tr').dataset.row,10);
      openFigureModalForRow(idx);
    });
  });
}


document.addEventListener('DOMContentLoaded', () => {
  byId('autoDistributeBtn')?.addEventListener('click', autoDistribute);
  // start with one empty row
  if (!tosRows.length) addTosRow();
});





/* ==============================================
   ============  Exam Generator Logic ===========
   ============================================== */

/* =================== Scheduler / gateway (kept) =================== */

// Cheap token estimator (~4 chars per token; count non-ASCII as 2 chars)
function approxTokens(x){
  const s = typeof x === 'string' ? x : JSON.stringify(x || '');
  const asciiWeighted = s.replace(/[^\x00-\x7F]/g, 'xx'); // non-ASCII ~2 bytes
  return Math.max(1, Math.ceil(asciiWeighted.length / 4));
}



const apikey = "Bearer exam-miner";
const LIMITS = { rpm: 8, tpm: 120000, windowMs: 60000 };
// Raise rpm to just under provider limit, add jitter, honor Retry-After

class GlobalScheduler {
  constructor() {
    this.q = [];
    this.state = { windowStart: Date.now(), reqs: 0, toks: 0, cooldownUntil: 0 };
    setInterval(() => this._tick(), 150);
  }
  _reset() {
    const now = Date.now();
    if (now - this.state.windowStart >= LIMITS.windowMs) {
      this.state.windowStart = now; this.state.reqs = 0; this.state.toks = 0;
    }
  }
  _tick() {
    this._reset();
    const s = this.state;
    const now = Date.now();
    if (now < s.cooldownUntil) return;
    if (!this.q.length) return;

    // smooth drip: at most 1 req every ~ (60s / rpm)
    const minGap = Math.ceil(LIMITS.windowMs / Math.max(1, LIMITS.rpm));
    if (s._lastSentAt && now - s._lastSentAt < minGap) return;

    const job = this.q[0];
    const nextReqs = s.reqs + 1, nextToks = s.toks + job.tokenCost;
    if (nextReqs > LIMITS.rpm || nextToks > LIMITS.tpm) return;

    this.q.shift();
    s.reqs = nextReqs; s.toks = nextToks; s._lastSentAt = now;
    job.run();
  }
  backoff(ms) {
    const jitter = Math.floor(Math.random() * 500);
    const until = Date.now() + Math.max(2000, (ms | 0)) + jitter;
    this.state.cooldownUntil = Math.max(this.state.cooldownUntil, until);
  }
  enqueue(tokenCost, fn) {
    return new Promise((resolve, reject) => {
      this.q.push({
        tokenCost: Math.max(1, tokenCost | 0),
        run: async () => {
          try {
            resolve(await fn());
          } catch (e) {
            reject(e);
          }
        }
      });
    });
  }
}
const scheduler = new GlobalScheduler();

async function scheduledJsonFetch({ url, init, tokenText, maxRetries = 8 }) {
  const tokenCost = approxTokens(tokenText || '') + approxTokens(init?.body || '');
  return scheduler.enqueue(tokenCost, async () => {
    let attempt = 0;
    while (true) {
      attempt++;
      let res, text;
      try {
        res = await fetch(url, init);
        text = await res.text();
      } catch (e) {
        if (attempt >= maxRetries) throw e;
        scheduler.backoff(1500 * attempt);
        await new Promise(r => setTimeout(r, 800 * attempt));
        continue;
      }

      let data = null;
      try { data = JSON.parse(text); } catch {}

      const ok = res.ok && data && (data.choices?.[0]?.message?.content || '').trim();
      if (ok) return data;

      // Handle retryable statuses & Retry-After
      const retryAfter = parseInt(res.headers.get('Retry-After') || '0', 10);
      const status = res.status;
      const retriable = status === 429 || status >= 500 || res.ok;
      if (!retriable || attempt >= maxRetries) {
        throw new Error((data?.error?.message || text || ('HTTP ' + status)) || 'Request failed');
      }
      if (status === 429) {
        scheduler.backoff((retryAfter ? retryAfter * 1000 : 20000));
      } else {
        scheduler.backoff(2000 + attempt * 500);
      }
      await new Promise(r => setTimeout(r, Math.min(35000, 1000 * Math.pow(1.7, attempt))));
    }
  });
}


/* ===== Row IO (no cleaning - gather raw Markdown + figures) ===== */
async function getRowMarkdownFromCard(card){
  const id = card.dataset.rowId;
  const files = rowState[id]?.files || [];
  const topic = (card.querySelector('.topicInput')?.value || "").trim();
  const safeTopic = topic || "This lesson";
  if (!files.length){
    return `# ${safeTopic}\n\n${safeTopic} covers core concepts, definitions, examples, procedures, and key properties.`;
  }
  const chunks=await Promise.all(files.map(async f=>{
    try{
      const md = await convertFileToMarkdown(f);
      return `# ${safeTopic} - ${f.name}\n\n${md}`;
    }catch(e){ console.warn("Convert failed:", f?.name, e); return ""; }
  }));
  return chunks.filter(Boolean).join(`\n\n---\n\n`);
}
function getChosenFiguresForCard(card){
  const id=card.dataset.rowId;
  const figs = rowState[id]?.figs || [];
  const sel = rowState[id]?.selectedFigIdx || new Set();
  const chosen = [];
  [...sel].sort((a,b)=>a-b).forEach(i=>{ if(figs[i]) chosen.push(figs[i]); });
  return chosen;
}

/* ===== TOS helpers ===== */
function buildTOSNumberMapFromRowsMeta(rowsMeta){
  const map={}; const push=(arr,lvl)=>arr.forEach(n=>{ map[n]=lvl; });
  rowsMeta.forEach(r=>{
    push(parseNums(r.cells.r),"remember");
    push(parseNums(r.cells.u),"understand");
    push(parseNums(r.cells.ap),"apply");
    push(parseNums(r.cells.an),"analyze");
    push(parseNums(r.cells.cr),"create");
    push(parseNums(r.cells.ev),"evaluate");
  });
  return map;
}

function countTotalNumbersFromRowsMeta(rowsMeta){
  const rows = normalizeRowsMeta(rowsMeta);
  const all = [];
  rows.forEach(r=>{
    const c = r?.cells || {};
    all.push(
      ...parseNums(c.r),  ...parseNums(c.u),
      ...parseNums(c.ap), ...parseNums(c.an),
      ...parseNums(c.cr), ...parseNums(c.ev)
    );
  });
  return new Set(all).size;
}


function planPartBuckets(N) {
  return { MCQ: Array.from({length:N}, (_,i)=>i+1)};
}


const BLOOM_TO_DIFF = { remember:'easy', understand:'easy', apply:'avg', analyze:'avg', create:'hard', evaluate:'hard' };
function difficultyOfLevel(level){ return BLOOM_TO_DIFF[level] || 'easy'; }
function difficultyCountsFor(bucketNums, tosMap){
  const c = { easy:0, avg:0, hard:0 };
  (bucketNums||[]).forEach(n=>{
    const lvl = tosMap[n];
    const d = difficultyOfLevel(lvl);
    c[d] = (c[d]||0) + 1;
  });
  return c;
}

function quotasFromBuckets(buckets, tosMap) {
  const nums = buckets.MCQ || [];
  const c = difficultyCountsFor(nums, tosMap);
  c.total = nums.length;
  return { mcq: c };
}


function allTopicsFromRows(rowsMeta){
  const list = rowsMeta.map(r => (r.topic||'').trim()).filter(Boolean);
  const uniq = Array.from(new Set(list.map(s=>s.toLowerCase()))).map(lc => list.find(x=>x.toLowerCase()===lc));
  return uniq.length ? uniq : ["(General Topic)"];
}

function buildSetPlansFromTOS(rowsMeta, numSets, numMap){
  const N = countTotalNumbersFromRowsMeta(rowsMeta);
  const topics = allTopicsFromRows(rowsMeta);
  const plans = [];
  for(let s=0; s<numSets; s++){
    const buckets = planPartBuckets(N);
    const quotas  = quotasFromBuckets(buckets, numMap);
    plans.push({
      set_id: s+1,
      buckets,
      quotas,
      topics: topics.slice(0,3)
    });
  }
  return plans;
}


/* =============== HELPERS =================== */


// Ultra-tolerant "sets" extractor that NEVER throws.
// Returns [] when nothing usable is found.
// Understands: {"sets":[...]}, {"tests":[...]}, [{"set_id":..,"mcq":[...]}], {"mcq":[...]}
// Also salvages balanced objects anywhere (even inside ```json ...```), and pure "mcq":[...] fragments.
function safeParseSets(raw) {
  const out = [];
  if (raw == null) return out;

  // ---- helpers ----
  const norm = (s) => String(s)
    .replace(/^\uFEFF/, "")
    .replace(/[\u200B-\u200D\u2060\uFEFF]/g, "")
    .replace(/[\u2028\u2029]/g, "\n")
    .replace(/[“”]/g, '"')
    .replace(/[‘’]/g, "'")
    .trim();

  const cleanJsonish = (txt) =>
    String(txt)
      .replace(/\/\/[^\n\r]*/g, "")         // // comments
      .replace(/\/\*[\s\S]*?\*\//g, "")     // /* comments */
      .replace(/,\s*([}\]])/g, "$1")        // trailing commas
      .trim();

  const tryParse = (txt) => {
    try { return JSON.parse(cleanJsonish(txt)); } catch { return null; }
  };

  const ensureSet = (o, fallbackId = 1) => {
    const id = (o && Number.isFinite(o.set_id)) ? o.set_id : fallbackId;
    const mcq = Array.isArray(o?.mcq) ? o.mcq : [];
    return { set_id: id, mcq };
  };

  const isSetLike = (x) => x && typeof x === 'object' && (Array.isArray(x.mcq) || 'set_id' in x);

  // Extract all balanced {...} from a string (very tolerant)
  function extractBalancedObjects(str) {
    const out = [];
    const n = str.length;
    for (let i = 0; i < n; i++) {
      if (str[i] !== '{') continue;
      let stack = 1, inStr = false, esc = false;
      for (let j = i + 1; j < n; j++) {
        const c = str[j];
        if (inStr) {
          if (esc) { esc = false; continue; }
          if (c === '\\') { esc = true; continue; }
          if (c === '"') inStr = false;
          continue;
        }
        if (c === '"') { inStr = true; continue; }
        if (c === '{') stack++;
        else if (c === '}') {
          stack--;
          if (!stack) { out.push(str.slice(i, j + 1)); i = j; break; }
        }
      }
    }
    return out;
  }

  // Extract content inside the first fenced block if present
  let s = norm(raw);
  // Remove full fences first
  const mFull = /```(?:jsonc?|json5)?\s*([\s\S]*?)```/i.exec(s);
  if (mFull && mFull[1]) s = mFull[1].trim();
  else {
    // Remove an opening fence without a closer as well
    const iFence = s.indexOf('```');
    if (iFence !== -1) s = s.slice(iFence + 3).trim();
  }
  // If text starts with 'json' word, drop it (models sometimes prefix it)
  s = s.replace(/^\s*json\b[:\s-]*/i, '').trim();

  // ---------- Phase 1: Fast parse whole thing ----------
  const j1 = tryParse(s);
  if (j1) {
    if (Array.isArray(j1.sets)) return j1.sets.map((o, i) => ensureSet(o, i + 1));
    if (Array.isArray(j1.tests)) return j1.tests.map((o, i) => ensureSet(o, i + 1));
    if (Array.isArray(j1) && j1.every(isSetLike)) return j1.map((o, i) => ensureSet(o, i + 1));
    if (isSetLike(j1)) return [ensureSet(j1, 1)];
    if (j1?.data?.sets && Array.isArray(j1.data.sets)) return j1.data.sets.map((o, i) => ensureSet(o, i + 1));
    if (Array.isArray(j1?.result) && j1.result.every(isSetLike)) return j1.result.map((o, i) => ensureSet(o, i + 1));
    // If it looks like {"mcq":[...]} only
    if (Array.isArray(j1?.mcq)) return [ensureSet(j1, 1)];
  }

  // ---------- Phase 2: Recover a "sets":[ ... ] array anywhere ----------
  // Grab the first [...] that follows a "sets": or "tests":
  const blockSets = /"(sets|tests)"\s*:\s*\[([\s\S]*?)\]/i.exec(s);
  if (blockSets) {
    const arrText = '[' + cleanJsonish(blockSets[2]) + ']';
    // Split into balanced objects inside the array
    const objs = extractBalancedObjects(arrText);
    const recovered = [];
    let idx = 1;
    for (const chunk of objs) {
      const o = tryParse(chunk);
      if (o && isSetLike(o)) recovered.push(ensureSet(o, idx++));
    }
    if (recovered.length) return recovered;
  }

  // ---------- Phase 3: Scan for ANY balanced object with mcq ----------
  {
    const objs = extractBalancedObjects(s.slice(0, 250000)); // cap scan
    const recovered = [];
    let nextId = 1;
    for (const chunk of objs) {
      const o = tryParse(chunk);
      if (o?.mcq && Array.isArray(o.mcq)) recovered.push(ensureSet(o, nextId++));
      else if (isSetLike(o)) recovered.push(ensureSet(o, nextId++));
      else if (o && o.set && isSetLike(o.set)) recovered.push(ensureSet(o.set, nextId++));
    }
    if (recovered.length) return recovered;
  }

  // ---------- Phase 4: Pure "mcq":[ ... ] salvage (no braces) ----------
  // Try to capture the array and wrap it ourselves.
  const mcqOnly = /"mcq"\s*:\s*\[([\s\S]*?)\]/i.exec(s);
  if (mcqOnly) {
    const arrStr = '[' + mcqOnly[1]
      .replace(/,\s*]/g, ']')      // trailing commas inside mcq array
      .replace(/,\s*}/g, '}') + ']';
    const wrapped = tryParse('{"mcq":' + arrStr + '}');
    if (wrapped && Array.isArray(wrapped.mcq)) return [ensureSet(wrapped, 1)];
  }

  // ---------- Phase 5: Nothing usable ----------
  console.warn('safeParseSets: could not salvage usable sets. Returning []. Sample:', s.slice(0, 400));
  return out;
}



function normalizeRowsMeta(rm){
  if (Array.isArray(rm)) return rm;
  if (rm && Array.isArray(rm.rows)) return rm.rows;       // sometimes wrapped
  if (rm && Array.isArray(rm.rowsMeta)) return rm.rowsMeta;
  return [];
}



// Safe getters with fallbacks
function getNumSets(){
  const el = byId('numSets');
  const v  = el?.value ?? window._lastNumSets ?? 1;
  const n  = parseInt(v, 10);
  return Math.max(1, Math.min(MAX_SETS, isNaN(n) ? 1 : n));
}
function getExamTitle(){
  const t = byId('examTitle')?.value ?? window._lastExamTitle ?? 'Exam';
  return (String(t).trim() || 'Exam');
}



async function askNumSets(){
  const s = prompt('How many sets do you want to create? (1–100)', (byId('numSets')?.value || String(window._lastNumSets || 1)));
  let n = Math.max(1, Math.min(MAX_SETS, parseInt(s||'1',10)||1));
  const el = byId('numSets');
  if (el) el.value = String(n);
  window._lastNumSets = n;              // <-- remember last chosen
  return n;
}


/* ======= Figure picker modal (per-row) ======= */

function ensureFigureModal(){
  if (byId('figModal')) return;

  // Inject once: scoped styles for a hard, reliable grid + thumbnails
  if (!byId('figModalStyles')) {
    const style = document.createElement('style');
    style.id = 'figModalStyles';
    style.textContent = `
      #figModal .modal-shell { width: 100%; max-width: 980px; }
      #figModal .fig-scroll   { max-height: 70vh; overflow: auto; }
      #figGrid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 12px;
      }
      /* Each cell is a perfect square via padding-top trick * /
      #figGrid .fig-item { font-size: 12px; text-align: center; }
      #figGrid .thumbBox {
        position: relative;
        width: 100%;
        padding-top: 100%;           
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
      }
      #figGrid .thumbBox img {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        max-width: 96%;
        max-height: 96%;
        object-fit: contain;         /* contain tall or wide images */
        display: block;
      }
      #figGrid .name { margin-top: 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
      /* Selection highlight * /
      #figGrid .fig-item.selected .thumbBox { outline: 3px solid #2563eb; outline-offset: 0; }
      #figGrid .chk { margin-top: 6px; }
    `;
    document.head.appendChild(style);
  }

  const div = document.createElement('div');
  div.id = 'figModal';
  div.className = 'fixed inset-0 bg-black/50 hidden z-50';
  div.innerHTML = `
    <div class="absolute inset-0 flex items-center justify-center p-4">
      <div class="modal-shell bg-white rounded-xl shadow-lg p-4">
        <div class="flex items-center justify-between mb-3">
          <div class="font-semibold">Choose Figures</div>
          <button type="button" id="figClose" class="text-sm underline">Close</button>
        </div>

        <div class="fig-scroll">
          <div id="figGrid"></div>
        </div>

        <div class="text-right mt-3">
          <button type="button" id="figApply" class="px-3 py-1 rounded bg-blue-600 text-white">Apply</button>
        </div>
      </div>
    </div>
  `;
  document.body.appendChild(div);
  byId('figClose').onclick = ()=> div.classList.add('hidden');
}

function openFigureModalForRow(idx){
  ensureFigureModal();
  const modal = byId('figModal');
  const grid  = byId('figGrid');
  const row   = tosRows[idx]; if(!row) return;

  grid.innerHTML = '';
  if (!row.figs.length){
    grid.innerHTML = `<div class="col-span-4 text-sm text-gray-600">No figures detected. Upload DOCX/PPTX/HTML/MD with images, then click Rescan (auto on upload).</div>`;
  } else {

row.figs.forEach((fg, i) => {
  const isSel = row.selected.has(i);

  const wrap = document.createElement('label');
  wrap.className = 'fig-item cursor-pointer select-none';
  wrap.innerHTML = `
    <div class="thumbBox">
      <input type="checkbox" class="chk" ${isSel ? 'checked' : ''} aria-label="Select figure ${i+1}">
      <img src="${fg.src}" alt="${escapeHTML(fg.name||'Figure')}">
    </div>
    <div class="name">${escapeHTML(fg.name || ('Figure ' + (i+1)))}</div>
  `;

  const chk = wrap.querySelector('.chk');

  // reflect initial selection state
  if (isSel) wrap.classList.add('selected');

  // toggle selection when checkbox changes
  chk.onchange = () => {
    if (chk.checked) {
      row.selected.add(i);
      wrap.classList.add('selected');
    } else {
      row.selected.delete(i);
      wrap.classList.remove('selected');
    }
  };

  // make the whole square clickable without fighting the checkbox
  wrap.addEventListener('click', (e) => {
    // ignore clicks that originated on the checkbox itself
    if (e.target === chk) return;
    chk.checked = !chk.checked;
    chk.dispatchEvent(new Event('change', { bubbles: false }));
  });

  grid.appendChild(wrap);
});

    

  }

  byId('figApply').onclick = ()=> { modal.classList.add('hidden'); /* selection already stored */ };
  modal.classList.remove('hidden');
}


// Put near your other helpers (next to urlToDataURL / blobToDataURL)
async function normalizeFiguresToData(figs){
  const out = [];
  for (const f of figs || []) {
    const data = await urlToDataURL(f?.src || '');
    if (data && data.startsWith('data:')) out.push({ ...f, src: data });
  }
  return out;
}


// --- Image inlining helpers ---

function blobToDataURL(blob){
  return new Promise((res, rej) => {
    const r = new FileReader();
    r.onload = () => res(String(r.result || ''));
    r.onerror = rej;
    r.readAsDataURL(blob);
  });
}

// Turn any URL (blob:, data:, http/https) into a data: URL if possible
async function urlToDataURL(src){
  if (!src) return '';
  if (src.startsWith('data:')) return src; // already embedded
  try {
    // fetch works on blob: and same-origin http(s) (CORS must allow it)
    const resp = await fetch(src, { mode: 'cors' });
    const blob = await resp.blob();
    return await blobToDataURL(blob);
  } catch (e) {
    console.warn('Inline image failed for', src, e);
    return src; // fall back to original src (may break after reload)
  }
}

// Replace ALL <img src="..."> inside given HTML with data: URLs
async function inlineAllImages(html){
  const node = document.createElement('div');
  node.innerHTML = html;

  const imgs = Array.from(node.querySelectorAll('img[src]'));
  for (const img of imgs){
    const src = img.getAttribute('src') || '';
    const data = await urlToDataURL(src);
    if (data && data.startsWith('data:')) {
      img.setAttribute('src', data);
      // (Optional) remove crossorigin to avoid docx/pdf quirks
      img.removeAttribute('crossorigin');
    }
  }
  return node.innerHTML;
}



// Figure policy: set which parts may include inline figures
const FIG_USE = {
  mcq: true,   // show figure on MCQ
};


// One-time allocator: returns each figure at most once (no reuse)
function makeFigureAllocator(figs = []) {
  const pool = Array.isArray(figs) ? figs.slice() : [];
  let i = 0;
  return {
    next() {                      // get next unused figure or null
      if (i >= pool.length) return null;
      const f = pool[i];
      i++;
      return f || null;
    }
  };
}


function figureImgHTML(fig, alt = 'Figure') {
  const src = fig?.src || '';
  if (!src) return ''; // never emit empty <img>
  return `<img src="${src}" alt="${escapeHTML(fig.name||alt)}"
           style="max-width:100%;height:auto;vertical-align:middle;margin-left:8px;border:1px solid #eee;padding:4px;border-radius:6px;">`;
}

// Split a number into N near-equal integer parts (e.g., 10 → [2,2,2,2,2] for N=5)
function splitCountAcrossAgents(total, nAgents) {
  const parts = Array(nAgents).fill(0);
  for (let i = 0; i < total; i++) parts[i % nAgents]++;
  return parts;
}

// Build atomic "tasks" from a plan’s quotas (one task = request for a single type with a count)
function buildTasksFromQuotas(plan, existingSet, nAgents) {
  const q = plan.quotas || {};
  const d = computeDeficits(existingSet, q);
  const tasks = [];

  // For each type, split the remaining needed count across agents
  const pushSplit = (type, cnt) => {
    if (!cnt) return;
    const parts = splitCountAcrossAgents(cnt, nAgents);
    parts.forEach((c) => { if (c > 0) tasks.push({ type, count: c }); });
  };

  pushSplit('mcq',   d.mcq);

  return tasks;
}

// Assign tasks round-robin to agents (always use all agents)
function assignTasksToAgents(tasks, agentsLen) {
  const buckets = Array.from({ length: agentsLen }, () => []);
  tasks.forEach((t, i) => buckets[i % agentsLen].push(t));
  return buckets;
}

// Merge newly generated items into the set (append-only)
function mergeSetInto(target, add) {
  const safeArr = (x) => Array.isArray(x) ? x : [];
  if (add.mcq) target.mcq = safeArr(target.mcq).concat(safeArr(add.mcq));
  return target;
}

// Trim arrays ONLY at the very end (after we’re sure we’re >= quotas)
function trimToQuotas(setObj, quotas) {
  const trim = (arr, want) => Array.isArray(arr) && arr.length > want ? arr.slice(0, want) : arr;
  if (quotas?.mcq) setObj.mcq = trim(setObj.mcq, quotas.mcq.total|0);
  return setObj;
}


// Super-tolerant TOON MCQ parser: no JSON.parse, works on "garbage" text.
function parseToonMcq(raw) {
  if (!raw) return { mcq: [] };

  let text = String(raw);

  // 1) Strip ``` fences (keep inner content)
  text = text.replace(/```[\s\S]*?```/g, block => {
    let inner = block.replace(/^```[^\n]*\n?/, "");
    inner = inner.replace(/```$/, "");
    return inner;
  });

  // 2) Remove leading "toon" word if present
  text = text.replace(/^\s*toon\b/i, "").trim();

  const mcq = [];

  // 3) Split into mcq blocks: "… mcq{ … } mcq{ … } …"
  const parts = text.split(/mcq\s*{/i);
  parts.shift(); // drop text before first mcq

  for (const part of parts) {
    // take up to the first closing brace – this is one MCQ body
    const body = part.split("}")[0];
    if (!body) continue;

    const objText = "{" + body + "}";

    // helper to grab a single quoted field
    const grab = (re) => {
      const m = objText.match(re);
      return m ? m[1].trim() : null;
    };

    const id    = grab(/id\s*:\s*"([\s\S]*?)"/i);
    const stem  = grab(/stem\s*:\s*"([\s\S]*?)"/i);
    const ansRaw = grab(/answer\s*:\s*"([\s\S]*?)"/i);

    // choices block
    let choices = [];
    const choicesMatch = objText.match(/choices\s*:\s*\[([\s\S]*?)\]/i);
    if (choicesMatch) {
      const inside = choicesMatch[1];
      const reChoice = /"([\s\S]*?)"/g;
      let cm;
      while ((cm = reChoice.exec(inside))) {
        const c = cm[1].trim();
        if (c) choices.push(c);
      }
    }

    // minimal validation
    if (!id || !stem || !choices.length) continue;

    // normalize answer to just "A/B/C/D"
    let answer = ansRaw || "";
    let m = answer.match(/\b([ABCD])\b/i) || answer.match(/^([ABCD])\)/i);
    if (m) {
      answer = m[1].toUpperCase();
    }

    mcq.push({ id, stem, choices, answer });
  }

  return { mcq };
}


async function callTopUp(agentIdx, kb, plan, existingSet) {
  const lane = AGENTS[agentIdx % AGENTS.length];
  const deficits = computeDeficits(existingSet, plan.quotas);
  if (!deficits.mcq) return existingSet;

  const topupSchema = `
mcq[]: {
  id: string,
  stem: string,
  choices: string[],
  answer: string
}
`;

  const msgSystem = `
You generate MCQs using TOON format ONLY.
Never use JSON. Never use Markdown. Never use code fences.

TOON Schema:
${topupSchema}
`.trim();

  const requests = { mcq: deficits.mcq };

  const payload = {
    model: lane.model,
    agent: lane.agent,
    temperature: 0.2,
    response_format: { type: "text" },
    messages: [
      { role: "system", content: msgSystem },
      {
        role: "user",
        content: `
set_id: ${plan.set_id}
need_mcq: ${deficits.mcq}

Generate ONLY the missing MCQs using TOON syntax.
Topics: ${JSON.stringify(kb.topics)}

--- MATERIAL ---
${kb.combined_markdown}
        `.trim()
      }
    ]
  };

  const data = await scheduledJsonFetch({
    url: "https://exammaker.site/api/v1/chat/completions.php",
    init: {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Authorization": apikey
      },
      body: JSON.stringify(payload)
    },
    tokenText: JSON.stringify({ set_id: plan.set_id, requests })
  });

  let raw = (data?.choices?.[0]?.message?.content || "").trim();

  // strip any accidental fences
  raw = raw
    .replace(/```+[a-zA-Z]*/g, "")
    .replace(/```/g, "")
    .trim();

  const toonObj = parseToonMcq(raw);
  const additional = (toonObj && Array.isArray(toonObj.mcq)) ? toonObj.mcq : [];

  existingSet.mcq = (existingSet.mcq || []).concat(additional);
  return existingSet;
}






async function generateOneSetCoop(kb, plan, initialSet = {}) {
  let s = {
    mcq: [],
    ...initialSet
  };

  const agentsLen = AGENTS.length; // use ALL agents, always
  let passes = 0;

  while (true) {
    passes++;
    const d = computeDeficits(s, plan.quotas);
    const stillNeed =
      d.mcq;

    if (!stillNeed) break; // done

    // Build atomic tasks from deficits and spread them across all agents
    const tasks = buildTasksFromQuotas(plan, s, agentsLen);
    if (!tasks.length) break;

    const buckets = assignTasksToAgents(tasks, agentsLen);

    // Each agent works its bucket in sequence; all agents run in parallel
    await Promise.all(buckets.map(async (agentTasks, agentIdx) => {
      for (const task of agentTasks) {
          // PATCH D: tiny stagger so agents don't fire at the same millisecond
    const base = agentIdx * 75; // per-agent offset (helps even more)
    await new Promise(r => setTimeout(r, 100 + base + Math.floor(Math.random() * 250)));
        const add = await callTopUp(agentIdx, kb, plan, s, task);
        mergeSetInto(s, add);
      }
    }));

    // Safety to prevent infinite loops on very thin material
    if (passes > 8) break;
  }

  // Final STRICT pass if we’re still short
  const dFinal = computeDeficits(s, plan.quotas);
  if (dFinal.mcq) {
    // Create one more round with strict=true and split again across agents
    const strictPlan = { ...plan, strict: true };
    const tasks = buildTasksFromQuotas(strictPlan, s, agentsLen);
    const buckets = assignTasksToAgents(tasks, agentsLen);
    await Promise.all(buckets.map(async (agentTasks, agentIdx) => {
      for (const task of agentTasks) {
          // PATCH D: tiny stagger so agents don't fire at the same millisecond
    const base = agentIdx * 75; // per-agent offset (helps even more)
    await new Promise(r => setTimeout(r, 100 + base + Math.floor(Math.random() * 250)));
        const add = await callTopUp(agentIdx, kb, strictPlan, s, task);
        mergeSetInto(s, add);
      }
    }));
  }

  // Only trim AFTER we know we’re at/over quotas
  return trimToQuotas(s, plan.quotas);
}


// =============

// Find ALL balanced JSON objects in raw text
function extractBalancedObjects(str) {
  const out = [];
  const pairs = { "{": "}", "[": "]" };
  for (let i = 0; i < str.length; i++) {
    if (str[i] !== "{") continue;
    let stack = ["{"], inStr = false, esc = false;
    for (let j = i + 1; j < str.length; j++) {
      const c = str[j];
      if (inStr) {
        if (esc) { esc = false; continue; }
        if (c === "\\") { esc = true; continue; }
        if (c === '"') inStr = false;
        continue;
      }
      if (c === '"') { inStr = true; continue; }
      if (c === "{") stack.push("{");
      else if (c === "}") {
        stack.pop();
        if (!stack.length) {
          out.push(str.slice(i, j + 1));
          i = j; // jump to end of this object
          break;
        }
      }
    }
  }
  return out;
}

function salvageSetsFromRaw(raw) {
  const objs = extractBalancedObjects(String(raw));
  const sets = [];
  for (const chunk of objs) {
    try {
      const o = JSON.parse(chunk.replace(/,\s*([}\]])/g, "$1"));
      if (isSetShape(o)) {
        sets.push(ensureSetArrays(o));
      } else if (o && o.set && isSetShape(o.set)) {
        sets.push(ensureSetArrays(o.set));
      }
    } catch {}
  }
  // Return only if we actually found at least one set-shaped object
  return sets.length ? sets : null;
}


function inlineImgHTML(f){
  return `<div class="mt-2">
    <img src="${f.src}" alt="${escapeHTML(f.name||'Figure')}"
         style="max-width:100%;max-height:180px;object-fit:contain;border:1px solid #eee;padding:6px;border-radius:8px;">
    ${f.name ? `<div class="small muted mt-1">${escapeHTML(f.name)}</div>` : ""}
  </div>`;
}

async function ensureAllSetsFilled(aiSets, plans, kb, agentIdx = 0){
  const got = new Map((aiSets||[]).map(s => [s.set_id, ensureSetArrays(s)]));
  const filled = [];

  // helper: how many items should exist (numbers 1..N) for this plan
  const desiredTotal = (plan) =>
    (plan.quotas?.mcq?.total|0);

  const actualTotal = (set) =>
    (set.mcq?.length||0);

  for (const plan of plans){
    let s = got.get(plan.set_id) || ensureSetArrays({ set_id: plan.set_id, meta:{} });

    // Cooperative fill using ALL agents (even for a single set)
const agentsLen = (Array.isArray(AGENTS) && AGENTS.length) ? AGENTS.length : 1;
let passes = 0;

while (true) {
  passes++;
  const d = computeDeficits(s, plan.quotas);
  const need = d.mcq;
  if (!need && actualTotal(s) === desiredTotal(plan)) break;

  const tasks = buildTasksFromQuotas(plan, s, agentsLen);
  if (!tasks.length) break;

  const buckets = assignTasksToAgents(tasks, agentsLen);

  // All agents run in parallel; each executes its small task list sequentially.
  await Promise.all(buckets.map(async (agentTasks, aIdx) => {
    for (const task of agentTasks) {
         const base = aIdx * 75;
         await new Promise(r => setTimeout(r, 100 + base + Math.floor(Math.random() * 250)));
      const add = await callTopUp(aIdx, kb, plan, s, task);
      mergeSetInto(s, add);
    }
  }));

  if (passes > 8) break; // safety
}

// STRICT final pass if still short
{
  const dFinal = computeDeficits(s, plan.quotas);
  const stillNeed = dFinal.mcq;
  if (stillNeed) {
    const strictPlan = { ...plan, strict: true };
    const tasks = buildTasksFromQuotas(strictPlan, s, agentsLen);
    const buckets = assignTasksToAgents(tasks, agentsLen);
    await Promise.all(buckets.map(async (agentTasks, aIdx) => {
      for (const task of agentTasks) {
           const base = aIdx * 75;
           await new Promise(r => setTimeout(r, 100 + base + Math.floor(Math.random() * 250)));
        const add = await callTopUp(aIdx, kb, strictPlan, s, task);
        mergeSetInto(s, add);
      }
    }));
  }
}

    // final sanity: trim any accidental overshoot (rare)
    const trim = (arr, want) => Array.isArray(arr) && arr.length > want ? arr.slice(0, want) : arr;
    s.mcq = trim(s.mcq, plan.quotas?.mcq?.total|0);

    // (Optional) attach a checklist meta
    s.meta = s.meta || {};
    s.meta.checklist = {
      want: {
        mcq: plan.quotas?.mcq?.total|0
      },
      have: {
        mcq: s.mcq.length
      },
      ok:
        (s.mcq.length === (plan.quotas?.mcq?.total|0))
    };

    filled.push(s);
  }

  filled.sort((a,b)=> (a.set_id||0)-(b.set_id||0));
  return filled;
}


// HARDENED: tolerant of unterminated ``` fences + truncated noise
function parseJSONLoose(raw) {
  if (!raw) throw new Error("Empty response");
  let s = String(raw)
    .replace(/^\uFEFF/, "")
    .replace(/[\u200B-\u200D\u2060\uFEFF]/g, "")
    .replace(/[\u2028\u2029]/g, "\n")
    .trim();

  // If starts a fenced block but never closes, keep everything after the first fence
  const firstFence = s.match(/```(?:jsonc?|json5)?\s*/i);
  if (firstFence) {
    const idx = s.indexOf(firstFence[0]);
    if (idx !== -1) {
      s = s.slice(idx + firstFence[0].length); // drop the opening ```json
      // do NOT require a closing fence — proceed with extraction below
    }
  } else {
    // If there IS a full fenced block, prefer its contents
    const fenceRe = /```(?:jsonc?|json5)?\s*([\s\S]*?)```/i;
    const m = fenceRe.exec(s);
    if (m && m[1]) s = m[1].trim();
  }

  // Normalize quotes
  s = s.replace(/[“”]/g, '"').replace(/[‘’]/g, "'");

  // Try a direct parse
  try { return JSON.parse(s); } catch {}

  // JSON5-ish cleanup
  let cleaned = s
    .replace(/\/\/[^\n\r]*/g, "")
    .replace(/\/\*[\s\S]*?\*\//g, "")
    .replace(/,\s*([}\]])/g, "$1")
    .trim();
  try { return JSON.parse(cleaned); } catch {}

  // Extract the largest balanced {...} or [...] region
  function extractLargestBalanced(str) {
    const pairs = { "{": "}", "[": "]" };
    let best = "", bestLen = 0;
    for (let i = 0; i < str.length; i++) {
      const open = str[i];
      if (!(open in pairs)) continue;
      let stack = [open], inStr = false, esc = false;
      for (let j = i + 1; j < str.length; j++) {
        const c = str[j];
        if (inStr) {
          if (esc) { esc = false; continue; }
          if (c === "\\") { esc = true; continue; }
          if (c === '"') inStr = false;
          continue;
        }
        if (c === '"') { inStr = true; continue; }
        if (c === pairs[stack[stack.length - 1]]) {
          stack.pop();
          if (!stack.length) {
            const chunk = str.slice(i, j + 1).replace(/,\s*([}\]])/g, "$1").trim();
            if (chunk.length > bestLen) { best = chunk; bestLen = chunk.length; }
            break;
          }
        } else if (c in pairs) stack.push(c);
      }
    }
    return best || null;
  }

  const largest = extractLargestBalanced(cleaned) || extractLargestBalanced(s);
  if (largest) { try { return JSON.parse(largest); } catch {} }

  // As a last resort, slice between first { and last }
  const fi = s.indexOf("{"), la = s.lastIndexOf("}");
  if (fi !== -1 && la !== -1 && la > fi) {
    const core = s.slice(fi, la + 1).replace(/,\s*([}\]])/g, "$1");
    try { return JSON.parse(core); } catch {}
  }

  console.warn("Malformed reply sample:", s.slice(0, 1200));
  throw new Error("Batch returned non-JSON");
}



/* ===== Multi-Agent (A1..A11) ===== */
const AGENTS = [
  { agent: 1, model: 'gemini/gemini-aistudio:free', label: 'A1' },
  { agent: 2, model: 'gemini/gemini-aistudio:free', label: 'A2' },
  { agent: 3, model: 'gemini/gemini-aistudio:free', label: 'A3' },
  { agent: 4, model: 'gemini/gemini-aistudio:free', label: 'A4' },
  { agent: 5, model: 'gemini/gemini-aistudio:free', label: 'A5' },
  { agent: 6, model: 'gemini/gemini-aistudio:free', label: 'A6' },
  { agent: 7, model: 'gemini/gemini-aistudio:free', label: 'A7' },
  { agent: 8, model: 'gemini/gemini-aistudio:free', label: 'A8' },
  { agent: 9, model: 'gemini/gemini-aistudio:free', label: 'A9' },
  { agent: 10, model: 'gemini/gemini-aistudio:free', label: 'A10' },
  { agent: 11, model: 'gemini/gemini-aistudio:free', label: 'A11' },
  { agent: 12, model: 'gemini/gemini-aistudio:free', label: 'A12' }
];
const MAX_SETS = 100;
const BATCH_SIZE = 1; // <=10 sets per batch



 // Compute per-type deficits vs quotas
function computeDeficits(setObj, quotas) {
  const need = (have, want) => Math.max(0, (want|0) - (Array.isArray(have) ? have.length : 0));
  return {
    mcq: quotas?.mcq ? need(setObj.mcq, quotas.mcq.total) : 0,
  };
}

// NEW: ask the agent to generate ONLY the missing portions for one set
async function callTopUp(agentIdx, kb, plan, existingSet) {

  const lane = AGENTS[agentIdx % AGENTS.length];
  const deficits = computeDeficits(existingSet, plan.quotas);
  if (!deficits.mcq) return existingSet;

  const topupSchema = `
mcq{
  id: string,
  stem: string,
  choices: array[string],
  answer: string
}[]:
`;

  const msgSystem = [
    "You are an MCQ generator.",
    "OUTPUT FORMAT: Use ONLY this TOON schema:",
    topupSchema,
    "Never output JSON, Markdown, or code fences."
  ].join("\n");

  const requests = { mcq: deficits.mcq };

  const payload = {
    model: lane.model,
    agent: lane.agent,
    temperature: 0.2,
    response_format: { type: "text" },
    messages: [
      { role: "system", content: msgSystem },
      {
        role: "user",
        content: `
Generate ONLY the missing MCQs.
set_id: ${plan.set_id}
need_mcq: ${deficits.mcq}
topics: ${JSON.stringify(kb.topics)}
--- markdown content ---
${kb.combined_markdown}
        `.trim()
      }
    ]
  };

  const data = await scheduledJsonFetch({
    url: "https://exammaker.site/api/v1/chat/completions.php",
    init: {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Authorization": apikey
      },
      body: JSON.stringify(payload)
    },
    tokenText: JSON.stringify({ set_id: plan.set_id, requests })
  });

  let raw = (data?.choices?.[0]?.message?.content || "").trim();

  // Optional: strip code fences once (parseToonMcq also tolerates them,
  // so this is just extra safety)
  raw = raw
    .replace(/```+[a-zA-Z]*/g, "") // remove ```toon / ```json / ```whatever
    .replace(/```/g, "")
    .trim();

  /* -------------- TOON → usable objects (via custom parser) -------------- */
  let toonObj;
  try {
    toonObj = parseToonMcq(raw);   // <-- your tolerant parser
  } catch (err) {
    console.warn("TopUp TOON parse error:", err, raw.slice(0, 300));
    toonObj = { mcq: [] };
  }

  let additional = [];
  if (toonObj && Array.isArray(toonObj.mcq)) {
    additional = toonObj.mcq;
  }

  // ---------- merge new MCQs into existing set ----------
  existingSet.mcq = (existingSet.mcq || []).concat(additional);

  return existingSet;
}



function getAllSelectedFigures(){
  const list = [];
  tosRows.forEach(row=>{
    [...row.selected].sort((a,b)=>a-b).forEach(i=>{
      if (row.figs[i]) list.push(row.figs[i]);
    });
  });
  return list;
}



// REPLACE makeKBFromRows_AI with this
function makeKBFromRows_AI(rowsMeta){
  const topics = allTopicsFromRows(rowsMeta);

  // Use the *actual* text we collected
  const materials = rowsMeta.map(r => ({
    topic: r.topic,
    // prefer raw_markdown; keep a fallback if you later add "cleaned"
    text: (r.raw_markdown && String(r.raw_markdown).trim())
          || (r.cleaned && String(r.cleaned).trim())
          || ""
  }));

  const combined_markdown = materials
    .map(m => {
      const body = m.text || "";
      return `# ${m.topic || "(Untitled)"}\n\n${body}`;
    })
    .join("\n\n---\n\n")
    .trim();

  return { topics, materials, combined_markdown };
}

function makeBatchMessages_AI(kb, setPlans){
  const schema = `{
  "sets": [
    {
      "set_id": <number>,
      "mcq": [ {"id":"MC1","stem":"...", "choices":["A) ...","B) ...","C) ...","D) ..."], "answer":"A|B|C|D"} ],
      "meta": {
        "topics_used": ["...","..."],
        "tos_check": {
          "mcq":{"easy":<n>,"avg":<n>,"hard":<n>,"total":<n>,"ok":true}
        }
      }
    }
  ]
}`;

  const forbidden = [
    "Bloom","TOS","Table of Specifications","difficulty",
    "easy level","average level","hard level",
    "this exam","this test","this set",
    "question prompt","your answer","choose the best answer"
  ];

  const kbIsThin = (kb.combined_markdown || "").length < 200;

  const style = [
  "Keep stems purely content-focused; do not mention Bloom/TOS/meta/testing language.",
  "STRICT: For each set_id in 'plans', you MUST return exactly one set with that set_id.",
  "STRICT: Meet quotas EXACTLY for mcq type. No missing or extra items.",
  "For each set, use ONLY content relevant to that set’s 'topics' array; ignore unrelated material even if present in combined_markdown.",
  "If materials are thin, you MAY use reasonable domain knowledge to satisfy quotas.",
  "No duplicate stems within a set. MCQ has 1 correct + 3 plausible (no All/None).",
  'STRICT: Top-level MUST be a single JSON object with a "sets" array: { "sets": [ { ... }, { ... } ] }. Do not return a single set, a top-level array, or extra keys.',
  "OUTPUT STRICT JSON ONLY with the exact schema. No markdown, no prose, no code fences. do not use ```json before the exam."
].join(" ");


  // Important: if KB is thin, allow the model to lean on topic names
  const grounding = kbIsThin
    ? "Use the provided topics as anchors and reasonable domain knowledge when the combined_markdown is brief."
    : "Prefer facts/terms from combined_markdown; ground each stem with at least one domain term found there.";

const system = `
You are an MCQ generator for an exam system.

STRICT OUTPUT FORMAT:
Use ONLY the TOON format below. Never output JSON, never output markdown code fences.

mcq{
  id: string,
  stem: string,
  choices: array[string],
  answer: string
}[]:
`;

/*
STRICT RULES:
- Reply with ONE minified JSON object only. No prose. No Markdown. No code fences.
- The top-level MUST be exactly: {"sets":[ ... ]}  (NOT "test", "tests", "result", etc.)
- Each element in "sets" MUST be:
  {"set_id": <number>,
   "mcq":[ {"id":"MC1","stem":"...","choices":["A) ...","B) ...","C) ...","D) ..."],"answer":"A|B|C|D"} ]
  }
- MCQ only. No other keys or sections.
- choices must be an array of EXACTLY 4 strings, each starting with "A) ", "B) ", "C) ", "D) ".
- answer must be one letter: "A" or "B" or "C" or "D".
- Do not include analysis, explanations, or comments in the JSON.
- If you cannot produce items, still return the schema with empty arrays for that set_id.

QUALITY RULES:
- Stems are clear, self-contained, and domain-relevant.
- 1 correct option + 3 plausible distractors. No "All of the above"/"None of the above".
- No duplicates within a set.

  You generate exam items aligned to a TOS but without meta talk. Return ONLY minified JSON. No prose, no code-fences, no keys outside the schema. Schema: {"sets":[{"set_id":1,"mcq":[{"id":"MC1","stem":"…","choices":["A) …","B) …","C) …","D) …"],"answer":"A"}]}]} ${style} ${grounding}
*/




  const setsBrief = setPlans.map(p=>({
    set_id: p.set_id,
    quotas: p.quotas,
    topics: p.topics
  }));

  return [
    { role: 'system', content: system },
    { role: 'user', content: JSON.stringify({
        knowledge_base: {
          topics: kb.topics,
          combined_markdown: kb.combined_markdown,
          forbidden_terms: forbidden
        },
        plans: setsBrief,
        schema
      })}
  ];
}


function chunkPlans(plans, size){ const out=[]; for(let i=0;i<plans.length;i+=size) out.push(plans.slice(i,i+size)); return out; }


function isSetShape(o){
  if(!o || typeof o !== 'object') return false;
  // minimal shape check
  return ('set_id' in o) && (
    Array.isArray(o.mcq)
  );
}

function normalizeSetsFromAny(json){
  if (json && Array.isArray(json.sets)) return json.sets;               // preferred
  if (json && Array.isArray(json.tests)) return json.tests;             // alias some models emit
  if (Array.isArray(json) && json.every(isSetShape)) return json;       // array of sets
  if (json && isSetShape(json.set)) return [json.set];                  // wrapped single
  if (json && isSetShape(json)) return [json];                          // single at top-level
  if (json && json.data && Array.isArray(json.data.sets)) return json.data.sets;
  if (json && Array.isArray(json.result) && json.result.every(isSetShape)) return json.result;
  return null;
}


// Safer no-op normalizer for arrays on a set
function ensureSetArrays(s){
  s.mcq = Array.isArray(s.mcq) ? s.mcq : [];
  return s;
}

// === Define the missing batch caller ===
/*
async function callAgentForBatch(agentIdx, kb, plansChunk) {
  const lane = AGENTS[agentIdx % AGENTS.length];

  // 1) Ask the model for all sets in this chunk
  const payload = {
    model: lane.model,
    agent: lane.agent,
    temperature: 0.2,
    response_format: { type: "text" },
    messages: makeBatchMessages_AI(kb, plansChunk)
  };

  const data = await scheduledJsonFetch({
    url: "https://exammaker.site/api/v1/chat/completions.php",
    init: {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Authorization": apikey
      },
      body: JSON.stringify(payload)
    },
    tokenText: JSON.stringify({ plans: plansChunk.map(p => p.set_id) })
  });

  // original raw (for JSON/salvage)
  const rawOriginal = (data?.choices?.[0]?.message?.content || "").trim();

  // cleaned version for TOON (remove ```toon / ```json, etc.)
  let raw = rawOriginal
    .replace(/```+[a-zA-Z]* /g, "") // remove ```toon, ```json, etc.
    .replace(/```/g, "")           // remove closing ```
    .trim();

  let sets = null;

  // --------- TRY TOON FIRST (but only if library exists) ----------
  if (typeof toon !== "undefined") {
    try {
      const toonObj = parseToonMcq(raw); //toon.parse(raw);

      if (toonObj) {
        // If your schema is something like: sets[] { ... }
        if (Array.isArray(toonObj.sets)) {
          sets = toonObj.sets;
        }
        // Or if model returns just mcq{...}
        else if (Array.isArray(toonObj.mcq)) {
          sets = [{
            set_id: plansChunk[0].set_id,
            mcq: toonObj.mcq
          }];
        }
      }

      if (!sets) {
        console.warn("No MCQ/sets parsed from TOON, will try JSON fallback.");
      }
    } catch (e) {
      console.warn("TOON parse failed:", e, raw.slice(0, 300));
    }
  } else {
    console.warn("TOON library not loaded; skipping TOON parse.");
  }

  // --------- JSON / LEGACY FALLBACK PIPELINE ----------
  if (!sets) {
    let json = null;
    try {
      json = parseJSONLoose(rawOriginal) || {};
    } catch (e) {
      // ignore, we’ll try salvage next
    }
    if (json) {
      sets = normalizeSetsFromAny(json);
    }
  }

  if (!sets) {
    const salvaged = salvageSetsFromRaw(rawOriginal);
    if (salvaged && salvaged.length) {
      sets = salvaged;
    }
  }

  if (!sets) {
    console.warn("Unexpected batch shape; raw sample:", rawOriginal.slice(0, 400));
    throw new Error("Malformed batch JSON/TOON");
  }

  // 3) Index by set_id and ensure array fields exist
  const got = new Map(
    sets.map(s => {
      const clean = ensureSetArrays(s || {});
      return [clean.set_id, clean];
    })
  );

  // 4) For every plan in this chunk, make sure we have a set and top-up deficits
  const normalized = [];
  for (const plan of plansChunk) {
    let s = got.get(plan.set_id) || ensureSetArrays({ set_id: plan.set_id, meta: {} });

    // fill any missing items, a few passes max
    for (let pass = 0; pass < 8; pass++) {
      const d = computeDeficits(s, plan.quotas);
      const need = d.mcq;
      if (!need) break;
      s = await callTopUp(agentIdx, kb, plan, s);
    }

    // sanity trim (if model overshot)
    const trim = (arr, want) =>
      Array.isArray(arr) && arr.length > want ? arr.slice(0, want) : arr;

    s.mcq = trim(s.mcq, plan.quotas?.mcq?.total | 0);
    normalized.push(s);
  }

  return normalized;
}
*/


async function callAgentForBatch(agentIdx, kb, plansChunk){
  const lane = AGENTS[agentIdx % AGENTS.length];

  // 1) Ask the model for all sets in this chunk
  const payload = {
    model: lane.model,
    agent: lane.agent,
    temperature: 0.2,
    response_format: { type:"text" },
    messages: makeBatchMessages_AI(kb, plansChunk)
  };

  const data = await scheduledJsonFetch({
    url: "https://exammaker.site/api/v1/chat/completions.php",
    init: {
      method: "POST",
      headers: { "Content-Type": "application/json", "Authorization": apikey },
      body: JSON.stringify(payload)
    },
    tokenText: JSON.stringify({ plans: plansChunk.map(p => p.set_id) })
  });

  const raw = (data?.choices?.[0]?.message?.content || "").trim();

  let sets = null;

  // ---- 1A) TRY TOON PARSER FIRST (handles ```toon + mcq{...}) ----
  /*
  const toonObj = parseToonMcq(raw);
  if (toonObj && Array.isArray(toonObj.mcq) && toonObj.mcq.length) {
    console.warn("Malformed reply sample (TOON salvage):", raw.slice(0, 300));
    sets = [{
      set_id: plansChunk[0].set_id,
      mcq: toonObj.mcq
    }];
  }
  */
  
        const toonObj = parseToonMcq(raw);
        if (!toonObj.mcq || !toonObj.mcq.length) {
          console.warn("Malformed reply sample (TOON salvage):", raw.slice(0,300));
        } else {
          // we got valid MCQs, no need to complain
          sets = [{
            set_id: plansChunk[0].set_id,
            mcq: toonObj.mcq
          }];
        }


  // ---- 2) NORMAL JSON PATH (only if TOON parser found nothing) ----
  if (!sets) {
    let json = null;
    try { json = parseJSONLoose(raw) || {}; } catch {}
    if (json) sets = normalizeSetsFromAny(json);
  }

  // ---- 3) Old salvage (regex-based) if still nothing ----
  if (!sets) {
    const salvaged = salvageSetsFromRaw(raw);
    if (salvaged && salvaged.length) sets = salvaged;
  }

  if (!sets) {
    console.warn("Unexpected batch shape; raw sample:", raw.slice(0, 400));
    throw new Error("Malformed batch JSON");
  }

  // 4) Index by set_id and ensure array fields exist
  const got = new Map(sets.map(s => {
    const clean = ensureSetArrays(s || {});
    return [clean.set_id, clean];
  }));

  // 5) For every plan, make sure we have a set and top-up deficits
  const normalized = [];
  for (const plan of plansChunk){
    let s = got.get(plan.set_id) || ensureSetArrays({ set_id: plan.set_id, meta:{} });

    // fill any missing items, a few passes max
    for (let pass = 0; pass < 8; pass++){
      const d = computeDeficits(s, plan.quotas);
      const need = d.mcq;
      if (!need) break;
      s = await callTopUp(agentIdx, kb, plan, s);
    }

    // sanity trim (if model overshot)
    const trim = (arr, want) => Array.isArray(arr) && arr.length > want ? arr.slice(0, want) : arr;
    s.mcq = trim(s.mcq, plan.quotas?.mcq?.total|0);
    normalized.push(s);
  }

  return normalized;
}




async function runBatchesParallel(kb, allPlans){
  const batches = chunkPlans(allPlans, BATCH_SIZE);
  const results = [];
  let nextBatch = 0;

  // const MAX_WORKERS = Math.min(2, batches.length); // <= 2 keeps pressure low
  const MAX_WORKERS = batches.length > 30 ? 1 : 2;

  // const workers = Array.from({ length: Math.min(AGENTS.length, batches.length) }, (_, workerIdx)
   const workers = Array.from({ length: MAX_WORKERS }, (_, workerIdx) => (async () => {
    while (true) {
      const myBatchIdx = nextBatch++;
      if (myBatchIdx >= batches.length) break;

      const plansChunk = batches[myBatchIdx];
      
      try {
      const part = await callAgentForBatch(workerIdx, kb, plansChunk);
      results.push(...part);
    } catch (err) {
      console.warn("Worker", workerIdx, "retrying batch", myBatchIdx, err);
      await new Promise(r => setTimeout(r, 3000));
      try {
        const part = await callAgentForBatch(workerIdx, kb, plansChunk);
        results.push(...part);
      } catch (err2) {
        console.warn("Worker", workerIdx, "giving up batch", myBatchIdx, err2);
        // swallow and continue; other batches still proceed
      }
    }

      
      setProgress(20 + Math.round((results.length / allPlans.length) * 60),
                  `Agents: ${results.length}/${allPlans.length} sets`);
    }
  })());

  await Promise.all(workers);
  results.sort((a,b)=> (a.set_id||0) - (b.set_id||0));
  return results;
}



function buildHTMLFromAISchemaSet(set, title, figs = []) {
  const num = set.set_id || 1;
  const sections = { MCQ:[]};
  const keyLines = [];
  let qnum = 1;
  const figAlloc = makeFigureAllocator(figs);
  
  const pushKey = (n, ans) => keyLines.push(`${n}. ${ans}`);

  // Insert a figure only for allowed types; supports [FIG] placeholder
function stemWithInlineFigureFor(type, rawStem, fallbackAlt = 'Figure') {
  const stemText = String(rawStem ?? '');
  const allowed = (type === 'mcq' && FIG_USE.mcq);
  if (!allowed) return escapeHTML(stemText);

  const fig = figAlloc.next();           // consume a unique figure (no reuse)
  if (!fig) return escapeHTML(stemText); // no more images left

  const figHTML = figureImgHTML(fig, fallbackAlt);
  if (/\[FIG\]/i.test(stemText)) {
    return escapeHTML(stemText).replace(/\[FIG\]/i, figHTML);
  }
  return `${escapeHTML(stemText)} ${figHTML}`;
}


  // MCQ
  (set.mcq||[]).forEach(it=>{
    // const stem = stemWithInlineFigure(it.stem, `Figure ${qnum}`);
    const stem = stemWithInlineFigureFor('mcq', it.stem, `Figure ${qnum}`);
    const opts = (Array.isArray(it.choices) ? it.choices : []).map(c=>escapeHTML(c)).join('<br>');
    sections.MCQ.push(
      `<div class="q">
         <div><strong>${qnum}.</strong> ${stem}</div>
         <div style="margin-top:6px">${opts}</div>
       </div>`
    );
    pushKey(qnum, it.answer || "");
    qnum++;
  });

  // Build the paper HTML (no header figure — all figures are inline now)
  const examHtml = `
    <div class="paper" contenteditable="true" data-paper>
      <header class="items-end justify-between gap-4 pb-3 mb-2">
      <span class="set"></span>
        <div class="title">${escapeHTML(title)} — SET ${num}</div>
      </header>
      ${ sections.MCQ.length   ? `<div class="font-semibold mt-4 mb-1">Multiple Choice</div>${sections.MCQ.join('')}`   : '' }
      <footer class="mt-6 pt-3 border-t border-dashed border-gray-200 small muted">Generated by Exam Maker</footer>
    </div>
  `;
   
   
  const keyHtml = `
    <div class="paper page-break" data-key>
      <div class="title font-bold text-lg mb-2">${escapeHTML(title)} — Answer Key (SET ${num})</div>
      <div class="small whitespace-pre-line">${escapeHTML(
        keyLines.slice().sort((A,B)=>parseInt(A)-parseInt(B)).join("\\n")
      )}</div>
    </div>
  `;
  return { examHtml, keyHtml, keyLines };
}



/* ===== Render & compile ===== */
function renderSets(list){
  const root = byId('sets');
  if (!root) return;
  root.innerHTML = '';
  list.forEach((s,i)=>{
    const wrap = document.createElement('div');
    const marked = s.examHtml.replace('<div class="paper"', '<div class="paper" data-paper');
    wrap.innerHTML = marked;
    root.appendChild(wrap);
    if (i < list.length - 1){
      const br = document.createElement('div');
      br.className = 'page-break';
      root.appendChild(br);
    }
  });
  const title = (byId('examTitle').value || 'Exam').trim();
  const compiled = document.createElement('div');
  const keysHTML = list.map((s, i)=>{
    const lines = (s.keyLines || []).slice().sort((A,B)=>parseInt(A)-parseInt(B));
    const li = lines.map(line=>{
      const m = String(line).match(/^\s*(\d+)\s*\.\s*(.*)$/);
      const num = m ? m[1] : '';
      const ans = m ? m[2] : String(line);
      return `<li><strong>${num}.</strong> ${escapeHTML(ans)}</li>`;
    }).join('');
    return `
      <section class="mt-3">
        <div class="font-semibold mb-1">SET ${i+1}</div>
        <ol style="column-gap:24px; -webkit-column-gap:24px; columns:2; -webkit-columns:2;">
          ${li || '<li>(no keys)</li>'}
        </ol>
      </section>
    `;
  }).join('');
  compiled.innerHTML = `
    <div class="page-break"></div>
    <div class="paper" data-compiled-keys>
      <div class="title font-bold text-lg mb-2">${escapeHTML(title)} - Answer Keys (All Sets)</div>
      ${keysHTML}
      <footer class="mt-6 pt-3 border-t border-dashed border-gray-200 small muted">Generated by Exam Maker - Agents</footer>
    </div>
  `;
  root.appendChild(compiled);
}
function stripHtmlToText(html = "") {
  const node = document.createElement('div');
  node.innerHTML = html;
  node.querySelectorAll('script,style').forEach(el=>el.remove());
  node.querySelectorAll('br').forEach(br=> br.replaceWith('\n'));
  const blockTags = ['p','div','header','footer','section','article','li','ul','ol'];
  blockTags.forEach(tag=>{
    node.querySelectorAll(tag).forEach(el=>{
      if (!/^\s*\n/.test(el.innerHTML)) el.insertAdjacentText('beforebegin','\n');
      if (!/\n\s*$/.test(el.innerHTML)) el.insertAdjacentText('afterend','\n');
    });
  });
  let text = node.textContent || "";
  return text.replace(/\r/g,"").replace(/[ \t]+\n/g,"\n").replace(/\n{3,}/g,"\n\n").trim();
}
function buildPlaintextFromSets(list, title) {
  const parts = [];
  const t = (title || 'Exam').trim();
  let totalQ = 0;
  list.forEach((s, i)=>{
     
    
    // Let the stripped HTML carry the header; don't pre-print our own
    const text = stripHtmlToText(s.examHtml);
    parts.push(text);


    const perSetQ = (text.match(/^\s*\d+\./gm) || []).length;
    totalQ += perSetQ;
    if (i < list.length - 1) parts.push("\n---\n");
  });
  parts.push("\n============================");
  parts.push("ANSWER KEYS - ALL SETS");
  parts.push("============================\n");
  list.forEach((s, i)=>{
    parts.push(`Answer Key - SET ${i+1}`);
    const sorted = (s.keyLines || []).slice().sort((A,B)=>parseInt(A)-parseInt(B));
    parts.push(sorted.join("\n") || "(no keys)");
    if (i < list.length - 1) parts.push("");
  });
  return { text: parts.join("\n"), totalQ };
}

/* =================== GENERATE (Agents only) =================== */
let lastCompiledSets = [];
let lastCompiledText = "";
let lastTotalQuestions = 0;


async function buildRowsMeta_AI(){
  const metas = await Promise.all(tosRows.map(async (row)=>{
    const topic = row.topic || "(Untitled)";
    let rawMd = "";
    if (!row.files.length){
      rawMd = `# ${topic}\n\n${topic} covers core concepts, definitions, examples, procedures, and key properties.`;
    } else {
      const parts = await Promise.all(row.files.map(async f=>{
        try{
          const md = await convertFileToMarkdown(f);
          return `# ${topic} - ${f.name}\n\n${md}`;
        }catch(e){ console.warn("Convert failed:", f?.name, e); return ""; }
      }));
      rawMd = parts.filter(Boolean).join("\n\n---\n\n");
    }
    // selected figures list
    const figsChosen = [...row.selected].sort((a,b)=>a-b).map(i=> row.figs[i]).filter(Boolean);
    return {
      id: row.id,
      topic,
      raw_markdown: rawMd || "",
      figures: figsChosen,
      cells: {
        r: row.cells.r.join(', '),
        u: row.cells.u.join(', '),
        ap: row.cells.ap.join(', '),
        an: row.cells.an.join(', '),
        cr: row.cells.cr.join(', '),
        ev: row.cells.ev.join(', ')
      }
    };
  }));
  return metas;
}


// Force MCQ-only everywhere this function runs
/*
async function generateAI(){
  // const types = { mcq:true};

  if (!tosRows.length) return showAlert('err','Add at least one TOS row.');

  const { rows, numMap } = buildTOS();
  const allNums = Array.from(new Set(Object.keys(numMap).map(n=>+n))).sort((a,b)=>a-b);
  if (!allNums.length) return showAlert('err','Provide TOS numbers (e.g., 1,2,3…) in the cells.');

  // SAFE: read sets & title without assuming DOM nodes exist
  const sets  = getNumSets();
  const title = getExamTitle();

  setLoader('Reading materials...','Converting files and preparing AI context');
  setProgress(10,'Files');


// was: const rowsMeta = await buildRowsMeta_AI(types);
const rowsMetaRaw = await buildRowsMeta_AI();
const rowsMeta    = normalizeRowsMeta(rowsMetaRaw);

if (!rowsMeta.length){
  hideLoader();
  return showAlert('err', 'No materials parsed for your TOS rows. Check uploads and make sure TOS numbers are filled.');
}


 // const rowsMeta = await buildRowsMeta_AI(types);

  const selectedFigs = rowsMeta.flatMap(r => r.figures || []);
  const figsData = await normalizeFiguresToData(selectedFigs);

  try{
    setProgress(45,'KB build');
    const kb = makeKBFromRows_AI(rowsMeta);

    setProgress(55,'Planning sets');
    const plans = buildSetPlansFromTOS(rowsMeta, sets, numMap);

    setProgress(60,'Calling agents');
    // const chosenFigs = getAllSelectedFigures(); // optional

    let aiSets = await runBatchesParallel(kb, plans);
    aiSets = await ensureAllSetsFilled(aiSets, plans, kb, 0);

    setProgress(85,'Render');
    const built = aiSets.map(s => buildHTMLFromAISchemaSet(s, title, figsData));
    byId('sets').innerHTML = '';
    renderSets(built);

    lastCompiledSets = built;
    const compiled = buildPlaintextFromSets(built, title);
    lastCompiledText = compiled.text;
    lastTotalQuestions = compiled.totalQ;
    const compiledEl = byId('compiledText');
    if (compiledEl) compiledEl.value = lastCompiledText;   // guard if textarea is missing

    hideLoader();
    showAlert('ok', `Draft(s) ready via Agents. Total questions: ${lastTotalQuestions}. You can save.`);
    const saveBtn = byId('btnSaveDbTop');
    if (saveBtn){ saveBtn.disabled = false; saveBtn.removeAttribute('title'); }
  }catch(e){
    console.error(e);
    hideLoader();
    showAlert('err','Agent generation failed.');
  }
}
*/

async function generateAI(){
  if (!tosRows.length) return showAlert('err','Add at least one TOS row.');

  const { rows, numMap } = buildTOS();
  const allNums = Array.from(new Set(Object.keys(numMap).map(n=>+n))).sort((a,b)=>a-b);
  if (!allNums.length) return showAlert('err','Provide TOS numbers (e.g., 1,2,3…) in the cells.');

  // SAFE: read sets & title without assuming DOM nodes exist
  const sets  = getNumSets();
  const title = getExamTitle();

  setLoader('Reading materials...','Converting files and preparing AI context');
  setProgress(10,'Files');

  const rowsMetaRaw = await buildRowsMeta_AI();
  const rowsMeta    = normalizeRowsMeta(rowsMetaRaw);

  if (!rowsMeta.length){
    hideLoader();
    return showAlert('err', 'No materials parsed for your TOS rows. Check uploads and make sure TOS numbers are filled.');
  }

  const selectedFigs = rowsMeta.flatMap(r => r.figures || []);
  const figsData = await normalizeFiguresToData(selectedFigs);

  setProgress(45,'KB build');
  const kb = makeKBFromRows_AI(rowsMeta);

  setProgress(55,'Planning sets');
  const plans = buildSetPlansFromTOS(rowsMeta, sets, numMap);

  let aiSets = [];
  try {
    setProgress(60,'Calling agents');
    aiSets = await runBatchesParallel(kb, plans);

    // Try to top-up any missing items/sets, but failure here should NOT block rendering:
    try {
      aiSets = await ensureAllSetsFilled(aiSets, plans, kb, 0);
    } catch (e) {
      console.warn("Top-up pass failed; rendering partial sets.", e);
    }
  } catch (e) {
    console.warn("Generation had errors; rendering whatever we have.", e);
  } finally {
    // keep only sets that actually meet their quotas
    const want = new Map(plans.map(p => [p.set_id, p.quotas?.mcq?.total|0]));
    const complete = aiSets.filter(s => (s?.mcq?.length||0) >= (want.get(s.set_id)||0));

    setProgress(85,'Render');
    const built = complete.map(s => buildHTMLFromAISchemaSet(s, title, figsData));
    byId('sets').innerHTML = '';
    renderSets(built);

    lastCompiledSets = built;
    const compiled = buildPlaintextFromSets(built, title);
    lastCompiledText = compiled.text;
    lastTotalQuestions = compiled.totalQ;
    const compiledEl = byId('compiledText');
    if (compiledEl) compiledEl.value = lastCompiledText;

    hideLoader();
    showAlert(complete.length ? 'ok' : 'err',
      complete.length
        ? `Finished with ${complete.length}/${plans.length} complete set(s).`
        : 'No complete sets produced. Try again or lower concurrency.'
    );

    const saveBtn = byId('btnSaveDbTop');
    if (saveBtn){ saveBtn.disabled = !complete.length; if (complete.length) saveBtn.removeAttribute('title'); }
  }
}



/* =================== Export & Save (kept) =================== */
function normalizePageBreaks(html){
  return html
    .replace(/<div class="page-break"><\/div>/g, '<div style="page-break-after: always;"></div>')
    .replace(/class="page-break"/g, 'style="page-break-after: always;"');
}
function getExamOnlyHTML(){
  const root = byId('sets'); if(!root) return '<div>No exam content</div>';
  const exams = Array.from(root.querySelectorAll('[data-paper]'))
    .map((el, idx, arr)=>{
      const edge = (idx < arr.length - 1) ? '<div class="page-break"></div>' : '';
      return el.outerHTML + edge;
    }).join('');
  return normalizePageBreaks(exams || '<div>No exam content</div>');
}
function getCompiledKeysHTML(){
  const root = byId('sets'); if(!root) return '<div>No keys</div>';
  const compiled = root.querySelector('[data-compiled-keys]');
  return normalizePageBreaks(compiled ? compiled.outerHTML : '<div>No keys</div>');
}
function currentHTML(){
  const sets = byId('sets'); if(!sets) return '';
  if (typeof DOMPurify === 'undefined') return sets.innerHTML;
  return DOMPurify.sanitize(sets.innerHTML, { ADD_ATTR:['style'] });
}
function downloadDOCX(filename, html){
  if (!window.htmlDocx || !window.saveAs){ showAlert('err','DOCX export requires html-docx-js and FileSaver.'); return; }
  const full = `<!DOCTYPE html><html><head><meta charset="utf-8"><style>body{font-family:Arial,sans-serif;font-size:12pt;line-height:1.5}</style></head><body>${html}</body></html>`;
  const blob = window.htmlDocx.asBlob(full); saveAs(blob, filename.endsWith('.docx')?filename:(filename+'.docx'));
}
function downloadPDF(filename, html){
  if (!window.htmlToPdfmake || !window.pdfMake){ showAlert('err','PDF export requires html-to-pdfmake and pdfMake.'); return; }
  const node=document.createElement('div'); node.innerHTML = html;
  node.querySelectorAll('script,style').forEach(el=>el.remove());
  const content = window.htmlToPdfmake(node.innerHTML, { window });
  const dd = { info:{title:filename}, pageSize:'A4', pageMargins:[40,60,40,60], content };
  pdfMake.createPdf(dd).download(filename.endsWith('.pdf')?filename:(filename+'.pdf'));
}



(function(){
  const $g = (id)=>document.getElementById(id);
  const bind = (id, fn)=>{ const el = $g(id); if (el) el.addEventListener('click', fn); };
  const title = ()=> (document.getElementById('examTitle')?.value || 'exam');

  bind('dlDocxExam',  async ()=>{
    const html = normalizePageBreaks(getExamOnlyHTML());
    const inlined = await inlineAllImages(html);
    downloadDOCX(`${title()}-sets`, inlined);
  });

  bind('dlPdfExam',   async ()=>{
    const html = normalizePageBreaks(getExamOnlyHTML());
    const inlined = await inlineAllImages(html);
    downloadPDF(`${title()}-sets`, inlined);
  });

  bind('dlDocxKey',   async ()=>{
    const html = normalizePageBreaks(getCompiledKeysHTML());
    const inlined = await inlineAllImages(html);
    downloadDOCX(`${title()}-keys`, inlined);
  });

  bind('dlPdfKey',    async ()=>{
    const html = normalizePageBreaks(getCompiledKeysHTML());
    const inlined = await inlineAllImages(html);
    downloadPDF(`${title()}-keys`, inlined);
  });
})();


/* ========= Generation binding ========= */

// if (byId('btnGenerate')) byId('btnGenerate').onclick = generateAI;

if (byId('btnGenerate')) byId('btnGenerate').onclick = async ()=>{
  const n = await askNumSets(); // stores into #numSets for your generateAI()
  await generateAI();
};

byId('examTitle')?.addEventListener('input', e => {
  window._lastExamTitle = e.target.value;
});



if (byId('btnSaveDbTop')) byId('btnSaveDbTop').onclick = async (e)=>{
  e?.preventDefault?.();

  // 1) Plain-text preview stays the same (no HTML)
  const compiledTextEl = byId('compiledText');
  const bodyText = (compiledTextEl?.value || "").trim();
  if (!bodyText) return showAlert('err','Nothing to save.');

  // 2) Count questions for DB meta
  let qty = (window.lastTotalQuestions||0);
  if (!qty || qty <= 0) { qty = (bodyText.match(/^\s*\d+\./gm) || []).length; }
  if (!qty || qty <= 0) return showAlert('err','No numbered questions found. Please generate again.');

  // 3) Build real HTML and inline images
  const htmlRendered = currentHTML(); // sanitized innerHTML of #sets
  const htmlClean    = normalizePageBreaks(htmlRendered) || '<div>No exam content</div>';
  const htmlWithImages = await inlineAllImages(htmlClean);   // <-- FIXED: use htmlClean

  // 4) Compose learning material names
  const names = tosRows.map((row)=> (row.files||[]).map(f=>f.name).join('; ')).join(' | ');

  // 5) Send to server
  const fd = new FormData();
  fd.append('token', jwt);
  fd.append('title', (byId('examTitle')?.value||'').trim() || 'Generated Exam');
  fd.append('description', '');
  fd.append('exam_type', 'mixed');
  fd.append('number_of_questions', String(qty));
  fd.append('sets_of_exam', byId('numSets')?.value || '1');
  fd.append('learning_material', names);
  fd.append('body_html', `<!DOCTYPE html><meta charset="utf-8">${htmlWithImages}`);

  try{
    const res = await fetch('/api/exam_save.php', { method:'POST', headers:{ 'Accept':'application/json' }, body: fd });
    const text = await res.text(); let data; try{ data=JSON.parse(text); }catch{ throw new Error(text); }
    if ((data && data.exam_id) || data?.status==='success'){
      showAlert('ok', data?.message || 'Saved! Opening your exam…');
      const id = data.exam_id || data.id; if(id) setTimeout(()=> location.href = `/exam/${id}`, 700);
    } else showAlert('err', data?.message || 'Failed to save exam.');
  }catch(err){ console.error(err); showAlert('err','Server error while saving.'); }
};

</script>
  
  </body>
 </html>
