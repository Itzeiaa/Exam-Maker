<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport"
        content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0"/>
  <title>Exam Maker — NLP Generator</title>
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
    #autoDistributeBtn, #addRowBtn, #removeRow, #rescan {
     font-size: 12px;
     padding: 10px 10px; 
     margin: 10px;
     border: transparent;
     box-shadow: 2px 2px 4px rgba(0,0,0,0.4);
     background: dodgerblue;
     color: white;
     border-radius: 4px;
    }
    
    #removeRow {
        background-color: #c0392b;
    }
    
    #autoDistributeBtn:hover, #addRowBtn:hover, #rescan:hover {
     background: rgb(2,0,36);
     background: linear-gradient(90deg, rgba(30,144,255,1) 0%, rgba(0,212,255,1) 100%);
    }
    
     #removeRow:hover {
     background:  #c0392b;
     background: linear-gradient(90deg, #c0392b 0%, #e74c3c 100%);
    }
    
    #autoDistributeBtn:active, #addRowBtn:active, #removeRow:active, #rescan:active {
     transform: translate(0em, 0.2em);
    }
    
    
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




    .container {
  --transition: 350ms;
  --folder-W: 120px;
  --folder-H: 80px;
  width: 300px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-end;
  padding: 10px;
  margin-top: 5em;
  background: linear-gradient(135deg, rgba(30,144,255,1) 0%, rgba(0,212,255,1)); /* linear-gradient(135deg, #f162ba, #ed45ae); */
  border-radius: 15px;
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
  height: calc(var(--folder-H) * 1.7);
  position: relative;
}

.folder {
  position: absolute;
  top: -20px;
  left: calc(50% - 60px);
  animation: float 2.5s infinite ease-in-out;
  transition: transform var(--transition) ease;
}

.folder:hover {
  transform: scale(1.05);
}

.folder .front-side,
.folder .back-side {
  position: absolute;
  transition: transform var(--transition);
  transform-origin: bottom center;
}

.folder .back-side::before,
.folder .back-side::after {
  content: "";
  display: block;
  background-color: white;
  opacity: 0.5;
  z-index: 0;
  width: var(--folder-W);
  height: var(--folder-H);
  position: absolute;
  transform-origin: bottom center;
  border-radius: 15px;
  transition: transform 350ms;
  z-index: 0;
}

.container:hover .back-side::before {
  transform: rotateX(-5deg) skewX(5deg);
}
.container:hover .back-side::after {
  transform: rotateX(-15deg) skewX(12deg);
}

.folder .front-side {
  z-index: 1;
}

.container:hover .front-side {
  transform: rotateX(-40deg) skewX(15deg);
}

.folder .tip {
  background: linear-gradient(135deg, #ff9a56, #ff6f56);
  width: 80px;
  height: 20px;
  border-radius: 12px 12px 0 0;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
  position: absolute;
  top: -10px;
  z-index: 2;
}

.folder .cover {
  background: linear-gradient(135deg, #ffe563, #ffc663);
  width: var(--folder-W);
  height: var(--folder-H);
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
  border-radius: 10px;
}

.custom-file-upload {
  font-size: 1.1em;
  color: #ffffff;
  text-align: center;
  background: rgba(255, 255, 255, 0.2);
  border: none;
  border-radius: 10px;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
  cursor: pointer;
  transition: background var(--transition) ease;
  display: inline-block;
  width: 100%;
  padding: 10px 35px;
  position: relative;
}

.custom-file-upload:hover {
  background: rgba(255, 255, 255, 0.4);
}

.custom-file-upload input[type="file"] {
  display: none;
}

@keyframes float {
  0% {
    transform: translateY(0px);
  }

  50% {
    transform: translateY(-20px);
  }

  100% {
    transform: translateY(0px);
  }
}


ol { padding-left: 1.25rem; }
ol li { margin: 2px 0; }

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
        <a href="/cms" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-xl mb-2"><i class="fas fa-clipboard-list mr-3"></i> CMS</a>
      </nav>

      <!-- Profile -->
      <div class="absolute bottom-0 w-64 p-6 border-t border-gray-100 bg-gray-50">
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
            <h1 class="text-4xl font-bold text-gray-900 mb-1">NLP-Powered Exam Generator</h1>
            <p class="text-gray-600">Use TOS + Bloom mapping. Materials per row → figures selection → generate sets → export/save.</p>
          </div>
        </div>
      </div>

      <!-- Alerts -->
      <div id="alertBox" class="hidden fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg"></div>

      <!-- Meta (no global upload here) -->
      <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-4">
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Exam Title</label>
            <input id="examTitle" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="e.g., Midterm — Computer Networks (OSI Model)"/>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1"># of Sets</label>
            <input id="numSets" type="number" min="1" max="150" value="1" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
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

          <div class="md:col-span-4">
            <div class="flex flex-wrap items-center gap-3">
              <span class="badge">Exam Types:</span>
              <label class="badge"><input type="checkbox" class="examType" value="mcq" checked> MCQ</label>
              <label class="badge"><input type="checkbox" class="examType" value="tf" checked> T/F</label>
              <label class="badge"><input type="checkbox" class="examType" value="id" checked> ID</label>
              <label class="badge"><input type="checkbox" class="examType" value="match" checked> Matching</label>
              <label class="badge"><input type="checkbox" class="examType" value="essay" checked> Essay</label>
            </div>
          </div>
        </div>
      </div>

      <!-- TOS + Bloom rows + Global totals controls -->
      <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-100">
          <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
            <h2 class="text-xl font-bold text-gray-900">Most Essential Learning Competencies (per Topic)</h2>
            <div class="flex flex-wrap items-center gap-2">
              <span class="badge">Easy <input id="gEasy" type="number" class="border rounded px-2 py-1" value="0" min="0" style="width:80px"></span>
              <span class="badge">Average <input id="gAvg" type="number" class="border rounded px-2 py-1" value="0" min="0" style="width:80px"></span>
              <span class="badge">Difficult <input id="gDiff" type="number" class="border rounded px-2 py-1" value="0" min="0" style="width:80px"></span>
              <button id="autoDistributeBtn" ><i class="fas fa-shuffle mr-1"></i>Auto-Distribute</button>
              <button id="addRowBtn" class="px-3 py-2 rounded border bg-gray-50 hover:bg-gray-100"><i class="fas fa-plus mr-1"></i>Add Row</button>
            </div>
          </div>
        </div>

        <div id="rows" class="p-6 grid gap-4"></div>

        <div class="px-6 pb-6">
          <div id="tosWarn" class="text-sm text-red-600 my-2 hidden"></div>
          <div id="tosMatrixWrap"></div>
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
          <!--div class="ml-auto flex items-center gap-2">
            <button id="dlPdfExam" class="px-3 py-2 border rounded hover:bg-gray-50"><i class="fas fa-file-pdf mr-2"></i>PDF (Exams)</button>
            <button id="dlPdfKey" class="px-3 py-2 border rounded hover:bg-gray-50"><i class="fas fa-file-pdf mr-2"></i>PDF (Keys)</button>
            <button id="dlDocxExam" class="px-3 py-2 border rounded hover:bg-gray-50"><i class="fas fa-file-word mr-2"></i>DOCX (Exams)</button>
            <button id="dlDocxKey" class="px-3 py-2 border rounded hover:bg-gray-50"><i class="fas fa-file-word mr-2"></i>DOCX (Keys)</button>
          </div-->
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
  
  
  <script> // TOP
  
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
        
        <div class="fileBadges flex flex-wrap gap-2 mt-2"></div>

        <div class="mt-3">
          <div class="flex items-center justify-between">
            <label class="text-sm font-medium">Figures (choose manually)</label>
            <button type="button" class="text-xs underline text-blue-600 refreshFigs">Rescan</button>
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

  card.querySelectorAll('textarea, .topicInput, .daysInput').forEach(el=> el.addEventListener('input', renderTOS));
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
  const cards=$$('#rows > div'); if(!cards.length) return;
  let i=0; nums.forEach(n=>{ const c=cards[i%cards.length]; const ta=c.querySelector(selector); const cur=parseNums(ta.value); cur.push(n); cur.sort((a,b)=>a-b); ta.value=cur.join(', '); i++; });
}
function autoDistribute(){
  const E=+byId('gEasy')?.value||0, A=+byId('gAvg')?.value||0, D=+byId('gDiff')?.value||0;
  const N=E+A+D; if(N<=0) return showAlert('err','Totals must be > 0.');
  if(!$$('#rows > div').length) return showAlert('err','Add at least one row first.');
  const nums = shuffle(Array.from({length:N},(_,i)=>i+1));
  const easy = nums.slice(0,E), avg = nums.slice(E,E+A), diff = nums.slice(E+A);
  const [R,U] = splitEvenly(easy);
  const [AP,AN] = splitEvenly(avg);
  const [CR,EV] = splitEvenly(diff);
  clearAllCells();
  roundRobinAssign(R,'.r');   roundRobinAssign(U,'.u');
  roundRobinAssign(AP,'.ap'); roundRobinAssign(AN,'.an');
  roundRobinAssign(CR,'.cr'); roundRobinAssign(EV,'.ev');
  renderTOS();
}

/* ===== Build TOS and render ===== */
function buildTOS(){
  const tableWrap = byId('tosMatrixWrap');
  const rows = $$('#rows > div').map((card, idx)=>{
    const topic = card.querySelector('.topicInput')?.value || `Row ${idx+1}`;
    const days = +card.querySelector('.daysInput')?.value || 0;
    const r=parseNums(card.querySelector('.r')?.value);
    const u=parseNums(card.querySelector('.u')?.value);
    const ap=parseNums(card.querySelector('.ap')?.value);
    const an=parseNums(card.querySelector('.an')?.value);
    const cr=parseNums(card.querySelector('.cr')?.value);
    const ev=parseNums(card.querySelector('.ev')?.value);
    const uniq = new Set([...r,...u,...ap,...an,...cr,...ev]);
    return { topic, days, cells:{r,u,ap,an,cr,ev}, total: uniq.size };
  });

  const map = {};
  const push=(arr,tag)=>arr.forEach(n=>map[n]=tag);
  rows.forEach(d=>{ push(d.cells.r,'remember'); push(d.cells.u,'understand'); push(d.cells.ap,'apply'); push(d.cells.an,'analyze'); push(d.cells.cr,'create'); push(d.cells.ev,'evaluate'); });

  return { rows, numMap: map, tableWrap };
}
function renderTOS(){
  const { rows, tableWrap } = buildTOS();
  if(!tableWrap) return;
  if(!rows.length){
    tableWrap.innerHTML=''; byId('tosWarn') && byId('tosWarn').classList.add('hidden'); return;
  }

  let sum={r:0,u:0,ap:0,an:0,cr:0,ev:0, days:0, items:0};
  rows.forEach(d=>{ sum.r+=d.cells.r.length; sum.u+=d.cells.u.length; sum.ap+=d.cells.ap.length; sum.an+=d.cells.an.length; sum.cr+=d.cells.cr.length; sum.ev+=d.cells.ev.length; sum.days+=d.days; sum.items+=d.total; });

  const grand = sum.items || 0;
  let percents = rows.map(r=> grand? (r.total/grand*100):0);
  let round2 = percents.map(x=> Math.round(x*100)/100);
  const deficit = Math.round((100 - round2.reduce((a,b)=>a+b,0))*100)/100;
  if(rows.length && Math.abs(deficit) >= 0.01){ round2[round2.length-1] = Math.round((round2[round2.length-1]+deficit)*100)/100; }

  const easyTotal = sum.r + sum.u;
  const avgTotal  = sum.ap + sum.an;
  const diffTotal = sum.cr + sum.ev;

  const warn = byId('tosWarn');
  if(warn){
    if(grand===0){ warn.textContent='No items yet. Type numbers or use Auto-Distribute.'; warn.classList.remove('hidden'); }
    else { const s = round2.reduce((a,b)=>a+b,0); const ok = Math.abs(s-100)<=0.05; warn.textContent = ok?'':'Warning: Percent total is not exactly 100% (shown '+s.toFixed(2)+'%).'; warn.classList.toggle('hidden', ok); }
  }

  const head = `
    <thead>
      <tr>
        <th rowspan="2">Most Essential Learning Competencies (Topic)</th>
        <th colspan="2">EASY (30%)</th>
        <th colspan="2">AVERAGE (60%)</th>
        <th colspan="2">DIFFICULT (10%)</th>
        <th rowspan="2">No. of days</th>
        <th rowspan="2">No. of items</th>
        <th rowspan="2">Percent in test</th>
      </tr>
      <tr>
        <th>Remembering</th><th>Understanding</th>
        <th>Applying</th><th>Analyzing</th>
        <th>Creating</th><th>Evaluating</th>
      </tr>
    </thead>
  `;
  const body = `
    <tbody>
      ${rows.map((d,i)=>`
        <tr>
          <td>${escapeHTML(d.topic)}</td>
          <td class="text-center">${d.cells.r.length}</td>
          <td class="text-center">${d.cells.u.length}</td>
          <td class="text-center">${d.cells.ap.length}</td>
          <td class="text-center">${d.cells.an.length}</td>
          <td class="text-center">${d.cells.cr.length}</td>
          <td class="text-center">${d.cells.ev.length}</td>
          <td class="text-center">${d.days}</td>
          <td class="text-center"><strong>${d.total}</strong></td>
          <td class="text-center">${(round2[i]||0).toFixed(2)}</td>
        </tr>
      `).join('')}
    </tbody>
  `;
  const foot = `
    <tfoot>
      <tr>
        <th>Totals</th>
        <th class="text-center">${sum.r}</th>
        <th class="text-center">${sum.u}</th>
        <th class="text-center">${sum.ap}</th>
        <th class="text-center">${sum.an}</th>
        <th class="text-center">${sum.cr}</th>
        <th class="text-center">${sum.ev}</th>
        <th class="text-center">${sum.days}</th>
        <th class="text-center"><strong>${sum.items}</strong></th>
        <th class="text-center"><strong>${(round2.reduce((a,b)=>a+b,0)).toFixed(2)}</strong></th>
      </tr>
      <tr>
        <th>Group totals</th>
        <th colspan="2" class="text-center">${easyTotal}</th>
        <th colspan="2" class="text-center">${avgTotal}</th>
        <th colspan="2" class="text-center">${diffTotal}</th>
        <th colspan="3"></th>
      </tr>
    </tfoot>
  `;
  tableWrap.innerHTML = `<div class="border rounded-xl p-4"><div class="font-semibold mb-2">Item Placement - Table of Specifications</div><div class="overflow-x-auto"><table class="tos">${head}${body}${foot}</table></div></div>`;
}

/* ========= Controls binding ========= */
document.addEventListener('DOMContentLoaded', () => {
  byId('addRowBtn')?.addEventListener('click', ()=>addRow());
  byId('autoDistributeBtn')?.addEventListener('click', autoDistribute);
  if (rowsEl()) addRow();
});

/* =================== Scheduler / gateway (kept) =================== */
const apikey = "Bearer exam-miner";
const LIMITS = { rpm: 6, tpm: 120000, windowMs: 60000 };
function approxTokens(str){ const n=(str||'').length; return Math.max(1, Math.ceil(n/4)); }
class GlobalScheduler{
  constructor(){ this.q=[]; this.state={windowStart:Date.now(),reqs:0,toks:0,cooldownUntil:0}; setInterval(()=>this._tick(),200); }
  _reset(){ const now=Date.now(); if(now-this.state.windowStart>=LIMITS.windowMs){ this.state.windowStart=now; this.state.reqs=0; this.state.toks=0; } }
  _tick(){ this._reset(); const s=this.state; const now=Date.now(); if(now<s.cooldownUntil || !this.q.length) return;
    const job=this.q[0]; const nextReqs=s.reqs+1, nextToks=s.toks+job.tokenCost;
    if(nextReqs>LIMITS.rpm || nextToks>LIMITS.tpm) return;
    this.q.shift(); s.reqs=nextReqs; s.toks=nextToks; job.run();
  }
  backoff(ms){ const until=Date.now()+Math.max(2000,ms|0); this.state.cooldownUntil=Math.max(this.state.cooldownUntil,until); }
  enqueue(tokenCost, fn){ return new Promise((resolve,reject)=>{ this.q.push({tokenCost:Math.max(1,tokenCost|0), run: async()=>{ try{ await new Promise(r=>setTimeout(r,150)); resolve(await fn()); }catch(e){ reject(e); } }}); }); }
}
const scheduler=new GlobalScheduler();
async function scheduledJsonFetch({url, init, tokenText, maxRetries=8}){
  const tokenCost = approxTokens(tokenText || '') + approxTokens(init?.body || '');
  return scheduler.enqueue(tokenCost, async ()=>{
    let attempt=0;
    while(true){
      attempt++;
      let res, text;
      try{ res=await fetch(url, init); text=await res.text(); }catch(e){
        if(attempt>=maxRetries) throw e;
        await new Promise(r=>setTimeout(r, 700*Math.pow(2,attempt-1))); continue;
      }
      let data=null; try{ data=JSON.parse(text); }catch{}
      const ok = res.ok && data && !data.error && (data.choices?.[0]?.message?.content||'').trim();
      if(ok) return data;
      const status=res.status; const retriable=(status===429||status>=500||res.ok);
      if(!retriable || attempt>=maxRetries){ throw new Error((data?.error?.message||text||('HTTP '+status))||'Request failed'); }
      if(status===429) scheduler.backoff(20000);
      await new Promise(r=>setTimeout(r, Math.min(30000, 1000*Math.pow(2,attempt-1))));
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
  const all=[];
  rowsMeta.forEach(r=>{
    all.push(...parseNums(r.cells.r),
             ...parseNums(r.cells.u),
             ...parseNums(r.cells.ap),
             ...parseNums(r.cells.an),
             ...parseNums(r.cells.cr),
             ...parseNums(r.cells.ev));
  });
  return Array.from(new Set(all)).sort((a,b)=>a-b).length;
}

const MAX_MATCH_PAIRS = 10;

function planPartBuckets(N, chosenTypes) {
  // Keep only enabled parts in the round-robin distribution
  const order = ["MCQ","TF","ID","MATCH","ESSAY"].filter(p=>{
    if(p==="MCQ")   return chosenTypes.mcq;
    if(p==="TF")    return chosenTypes.tf;
    if(p==="ID")    return chosenTypes.id;
    if(p==="MATCH") return chosenTypes.match;
    if(p==="ESSAY") return chosenTypes.essay;
  });

  const buckets = {MCQ:[],TF:[],ID:[],MATCH:[],ESSAY:[]};
  if (!order.length) return buckets;

  // Evenly allocate N *item slots* to the enabled parts
  const desired = {};
  const base = Math.floor(N / order.length);
  let rem = N % order.length;
  order.forEach((p,i)=> desired[p] = base + (i < rem ? 1 : 0));

  // SPECIAL: Matching = exactly ONE group, size = min(10, share)
  // Any leftover slots that don't fit the single group are redistributed to others.
  let matchingPairs = 0;
  if (chosenTypes.match) {
    const share = desired.MATCH || 0;
    if (share > 0) {
      matchingPairs = Math.min(MAX_MATCH_PAIRS, share); // number of pairs in the single group
      desired.MATCH = 1;                                 // ONE group only
      let leftover = share - matchingPairs;              // extra slots → give to others
      if (leftover > 0) {
        const others = order.filter(p => p !== "MATCH");
        for (let i = 0; i < leftover; i++) {
          const p = others[i % others.length];
          desired[p] = (desired[p] || 0) + 1;
        }
      }
    } else {
      desired.MATCH = 0;  // zero groups if no share
    }
  }

  // Turn the desired counts into sequential number buckets (1..N)
  let cur = 1;
  for (const p of order) {
    const count = desired[p] || 0;     // NOTE: for MATCH this is number of groups (0 or 1)
    for (let i = 0; i < count; i++) buckets[p].push(cur++);
  }

  // Store the single-group size for quotas
  buckets.__matching_pairs = matchingPairs; // 0..10
  return buckets;
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
  const pairsPerGroup = buckets.__matching_pairs || 0;     // 0..10
  const groups        = (buckets.MATCH || []).length;      // 0 or 1

  const q = {
    mcq:   difficultyCountsFor(buckets.MCQ  || [], tosMap),
    tf:    difficultyCountsFor(buckets.TF   || [], tosMap),
    ident: difficultyCountsFor(buckets.ID   || [], tosMap),
    essay: difficultyCountsFor(buckets.ESSAY|| [], tosMap),
    matching: {
      groups,
      pairs_per_group: pairsPerGroup,
      total_pairs: groups * pairsPerGroup
    }
  };
  q.mcq.total   = (buckets.MCQ   || []).length;
  q.tf.total    = (buckets.TF    || []).length;
  q.ident.total = (buckets.ID    || []).length;
  q.essay.total = (buckets.ESSAY || []).length;
  return q;
}


function allTopicsFromRows(rowsMeta){
  const list = rowsMeta.map(r => (r.topic||'').trim()).filter(Boolean);
  const uniq = Array.from(new Set(list.map(s=>s.toLowerCase()))).map(lc => list.find(x=>x.toLowerCase()===lc));
  return uniq.length ? uniq : ["(General Topic)"];
}

function buildSetPlansFromTOS(rowsMeta, chosenTypes, numSets, numMap){
  const N = countTotalNumbersFromRowsMeta(rowsMeta);
  const topics = allTopicsFromRows(rowsMeta);
  const plans = [];
  for(let s=0; s<numSets; s++){
    const buckets = planPartBuckets(N, chosenTypes, 5);
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
// helper: cycle through a list of figures across questions

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
  tf:  true    // show figure on True/False
  // identification / matching / essay intentionally excluded
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
  pushSplit('tf',    d.tf);
  pushSplit('ident', d.ident);
  pushSplit('essay', d.essay);
  pushSplit('matching', d.matchingGroups); // count = groups

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
  if (add.mcq)            target.mcq            = safeArr(target.mcq).concat(safeArr(add.mcq));
  if (add.true_false)     target.true_false     = safeArr(target.true_false).concat(safeArr(add.true_false));
  if (add.identification) target.identification = safeArr(target.identification).concat(safeArr(add.identification));
  if (add.essay)          target.essay          = safeArr(target.essay).concat(safeArr(add.essay));
  if (add.matching)       target.matching       = safeArr(target.matching).concat(safeArr(add.matching));
  return target;
}

// Trim arrays ONLY at the very end (after we’re sure we’re >= quotas)
function trimToQuotas(setObj, quotas) {
  const trim = (arr, want) => Array.isArray(arr) && arr.length > want ? arr.slice(0, want) : arr;
  if (quotas?.mcq)      setObj.mcq            = trim(setObj.mcq,            quotas.mcq.total|0);
  if (quotas?.tf)       setObj.true_false     = trim(setObj.true_false,     quotas.tf.total|0);
  if (quotas?.ident)    setObj.identification = trim(setObj.identification, quotas.ident.total|0);
  if (quotas?.essay)    setObj.essay          = trim(setObj.essay,          quotas.essay.total|0);
  if (quotas?.matching) setObj.matching       = trim(setObj.matching,       quotas.matching.groups|0);
  return setObj;
}


async function callTopUpForTask(agentIdx, kb, plan, existingSet, task) {
  // Build a micro-plan that requests ONLY the task’s type
  const oneTypePlan = {
    ...plan,
    strict: plan.strict ?? true,
    quotas: {
      mcq:      task.type === 'mcq'      ? { total: task.count } : undefined,
      tf:       task.type === 'tf'       ? { total: task.count } : undefined,
      ident:    task.type === 'ident'    ? { total: task.count } : undefined,
      essay:    task.type === 'essay'    ? { total: task.count } : undefined,
      matching: task.type === 'matching' ? { groups: task.count } : undefined,
    }
  };

  // Strengthen the system/assistant notes inside callTopUp (see section 4 below)
  const add = await callTopUp(agentIdx, kb, oneTypePlan, existingSet);

  // Basic empty-payload retry (helps with occasional blank LLM responses)
  const isEmpty =
    (!add?.mcq?.length) &&
    (!add?.true_false?.length) &&
    (!add?.identification?.length) &&
    (!add?.essay?.length) &&
    (!add?.matching?.length);

  if (isEmpty) {
    await new Promise(r => setTimeout(r, 600 + Math.floor(Math.random()*200)));
    return await callTopUp(agentIdx, kb, oneTypePlan, existingSet);
  }

  return add || {};
}



async function generateOneSetCoop(kb, plan, initialSet = {}) {
  let s = {
    mcq: [], true_false: [], identification: [], essay: [], matching: [],
    ...initialSet
  };

  const agentsLen = AGENTS.length; // use ALL agents, always
  let passes = 0;

  while (true) {
    passes++;
    const d = computeDeficits(s, plan.quotas);
    const stillNeed =
      d.mcq || d.tf || d.ident || d.essay || d.matchingGroups;

    if (!stillNeed) break; // done

    // Build atomic tasks from deficits and spread them across all agents
    const tasks = buildTasksFromQuotas(plan, s, agentsLen);
    if (!tasks.length) break;

    const buckets = assignTasksToAgents(tasks, agentsLen);

    // Each agent works its bucket in sequence; all agents run in parallel
    await Promise.all(buckets.map(async (agentTasks, agentIdx) => {
      for (const task of agentTasks) {
        const add = await callTopUpForTask(agentIdx, kb, plan, s, task);
        mergeSetInto(s, add);
      }
    }));

    // Safety to prevent infinite loops on very thin material
    if (passes > 8) break;
  }

  // Final STRICT pass if we’re still short
  const dFinal = computeDeficits(s, plan.quotas);
  if (dFinal.mcq || dFinal.tf || dFinal.ident || dFinal.essay || dFinal.matchingGroups) {
    // Create one more round with strict=true and split again across agents
    const strictPlan = { ...plan, strict: true };
    const tasks = buildTasksFromQuotas(strictPlan, s, agentsLen);
    const buckets = assignTasksToAgents(tasks, agentsLen);
    await Promise.all(buckets.map(async (agentTasks, agentIdx) => {
      for (const task of agentTasks) {
        const add = await callTopUpForTask(agentIdx, kb, strictPlan, s, task);
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
    (plan.quotas?.mcq?.total|0) +
    (plan.quotas?.tf?.total|0) +
    (plan.quotas?.ident?.total|0) +
    (plan.quotas?.essay?.total|0) +
    (plan.quotas?.matching?.groups|0);

  const actualTotal = (set) =>
    (set.mcq?.length||0) +
    (set.true_false?.length||0) +
    (set.identification?.length||0) +
    (set.essay?.length||0) +
    (set.matching?.length||0); // 1 number per matching group

  for (const plan of plans){
    let s = got.get(plan.set_id) || ensureSetArrays({ set_id: plan.set_id, meta:{} });

    // Cooperative fill using ALL agents (even for a single set)
const agentsLen = (Array.isArray(AGENTS) && AGENTS.length) ? AGENTS.length : 1;
let passes = 0;

while (true) {
  passes++;
  const d = computeDeficits(s, plan.quotas);
  const need = d.mcq || d.tf || d.ident || d.essay || d.matchingGroups;
  if (!need && actualTotal(s) === desiredTotal(plan)) break;

  const tasks = buildTasksFromQuotas(plan, s, agentsLen);
  if (!tasks.length) break;

  const buckets = assignTasksToAgents(tasks, agentsLen);

  // All agents run in parallel; each executes its small task list sequentially.
  await Promise.all(buckets.map(async (agentTasks, aIdx) => {
    for (const task of agentTasks) {
      const add = await callTopUpForTask(aIdx, kb, plan, s, task);
      mergeSetInto(s, add);
    }
  }));

  if (passes > 8) break; // safety
}

// STRICT final pass if still short
{
  const dFinal = computeDeficits(s, plan.quotas);
  const stillNeed = dFinal.mcq || dFinal.tf || dFinal.ident || dFinal.essay || dFinal.matchingGroups;
  if (stillNeed) {
    const strictPlan = { ...plan, strict: true };
    const tasks = buildTasksFromQuotas(strictPlan, s, agentsLen);
    const buckets = assignTasksToAgents(tasks, agentsLen);
    await Promise.all(buckets.map(async (agentTasks, aIdx) => {
      for (const task of agentTasks) {
        const add = await callTopUpForTask(aIdx, kb, strictPlan, s, task);
        mergeSetInto(s, add);
      }
    }));
  }
}

    // final sanity: trim any accidental overshoot (rare)
    const trim = (arr, want) => Array.isArray(arr) && arr.length > want ? arr.slice(0, want) : arr;
    s.mcq = trim(s.mcq, plan.quotas?.mcq?.total|0);
    s.true_false = trim(s.true_false, plan.quotas?.tf?.total|0);
    s.identification = trim(s.identification, plan.quotas?.ident?.total|0);
    s.essay = trim(s.essay, plan.quotas?.essay?.total|0);
    s.matching = trim(s.matching, plan.quotas?.matching?.groups|0);

    // (Optional) attach a checklist meta
    s.meta = s.meta || {};
    s.meta.checklist = {
      want: {
        mcq: plan.quotas?.mcq?.total|0,
        tf: plan.quotas?.tf?.total|0,
        ident: plan.quotas?.ident?.total|0,
        essay: plan.quotas?.essay?.total|0,
        matching_groups: plan.quotas?.matching?.groups|0
      },
      have: {
        mcq: s.mcq.length,
        tf: s.true_false.length,
        ident: s.identification.length,
        essay: s.essay.length,
        matching_groups: s.matching.length
      },
      ok:
        (s.mcq.length === (plan.quotas?.mcq?.total|0)) &&
        (s.true_false.length === (plan.quotas?.tf?.total|0)) &&
        (s.identification.length === (plan.quotas?.ident?.total|0)) &&
        (s.essay.length === (plan.quotas?.essay?.total|0)) &&
        (s.matching.length === (plan.quotas?.matching?.groups|0))
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



/* ===== Multi-Agent (A1..A7) ===== */
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
];
const MAX_SETS = 100;
const BATCH_SIZE = 1; // <=10 sets per batch



 // Compute per-type deficits vs quotas
function computeDeficits(setObj, quotas) {
  const need = (have, want) => Math.max(0, (want|0) - (Array.isArray(have) ? have.length : 0));
  return {
    mcq:            quotas?.mcq ? need(setObj.mcq, quotas.mcq.total) : 0,
    tf:             quotas?.tf ? need(setObj.true_false, quotas.tf.total) : 0,
    ident:          quotas?.ident ? need(setObj.identification, quotas.ident.total) : 0,
    essay:          quotas?.essay ? need(setObj.essay, quotas.essay.total) : 0,
    matchingGroups: quotas?.matching ? need(setObj.matching, quotas.matching.groups) : 0,
  };
}

// NEW: ask the agent to generate ONLY the missing portions for one set
async function callTopUp(agentIdx, kb, plan, existingSet){
    
    
  const lane = AGENTS[agentIdx % AGENTS.length];
  const deficits = computeDeficits(existingSet, plan.quotas);
  const needAny = deficits.mcq||deficits.tf||deficits.ident||deficits.essay||deficits.matchGroups;
  if(!needAny) return existingSet;

  const topupSchema = `{
    "set_id": ${plan.set_id},
    "true_false": [ {"id":"TF#","stem":"...","answer":"True|False"} ],
    "identification": [ {"id":"ID#","prompt":"...","answer":"..."} ],
    "mcq": [ {"id":"MC#","stem":"...","choices":["A) ...","B) ...","C) ...","D) ..."],"answer":"A|B|C|D"} ],
    "matching": [ { "id":"MT#",
                    "columnA":["A. ...","B. ...","C. ...","D. ...","E. ..."],
                    "columnB":["a. ...","b. ...","c. ...","d. ...","e. ..."],
                    "answer_key":{"A":"c","B":"e","C":"b","D":"a","E":"d"} } ],
    "essay": [ {"id":"ES#","prompt":"...","guidance":"(2–3 short paragraphs touching X, Y, Z)"} ]
  }`;

  const msgSystem = [
    "Generate ONLY the missing items to meet quotas for this single set.",
    "Do NOT repeat or modify existing items; add new ones only.",
    "If materials are thin, you MAY use reasonable domain knowledge.",
    "Return a single JSON object with arrays ONLY for the requested counts."
  ].join(" ");

  const requests = {
    mcq: deficits.mcq, tf: deficits.tf, ident: deficits.ident, essay: deficits.essay,
    matchGroups: deficits.matchingGroups, pairsPerGroup: plan.quotas.matching?.pairs_per_group || 5
  };

  const payload = {
    model: lane.model,
    agent: lane.agent,
    temperature: 0.2,
    response_format: { type: "json_object" },
    messages: [
      { role: "system", content: msgSystem },
      { role: "user", content: JSON.stringify({
          knowledge_base: { topics: kb.topics, combined_markdown: kb.combined_markdown },
          set_id: plan.set_id, requests, schema: topupSchema
        })}
    ]
  };
  

  const data = await scheduledJsonFetch({
    url: "https://exammaker.site/api/v1/chat/completions.php",
    init: { method:'POST', headers:{ 'Content-Type':'application/json','Authorization': apikey }, body: JSON.stringify(payload) },
    tokenText: JSON.stringify({set_id: plan.set_id, requests})
  });
  const raw = (data?.choices?.[0]?.message?.content || "").trim();
  let add = parseJSONLoose(raw);

  // Normalize missing arrays to []
  add = add || {};
  add.true_false = add.true_false || [];
  add.identification = add.identification || [];
  add.mcq = add.mcq || [];
  add.matching = add.matching || [];
  add.essay = add.essay || [];

  // Merge
  existingSet.true_false = (existingSet.true_false||[]).concat(add.true_false);
  existingSet.identification = (existingSet.identification||[]).concat(add.identification);
  existingSet.mcq = (existingSet.mcq||[]).concat(add.mcq);
  existingSet.matching = (existingSet.matching||[]).concat(add.matching);
  existingSet.essay = (existingSet.essay||[]).concat(add.essay);

  return existingSet;
}


// NEW: gather all selected figures across rows as a flat list
function getAllSelectedFigures(){
  const cards = $$('#rows > div');
  const list = [];
  cards.forEach(card=>{
    const id = card.dataset.rowId;
    const figs = (rowState[id]?.figs)||[];
    const sel  = (rowState[id]?.selectedFigIdx)||new Set();
    [...sel].sort((a,b)=>a-b).forEach(i=>{ if(figs[i]) list.push(figs[i]); });
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
      "true_false": [ {"id":"TF1","stem":"...", "answer":"True|False"} ],
      "identification": [ {"id":"ID1","prompt":"...", "answer":"..."} ],
      "mcq": [ {"id":"MC1","stem":"...", "choices":["A) ...","B) ...","C) ...","D) ..."], "answer":"A|B|C|D"} ],
      "matching": [ { "id":"MT1", "columnA": ["A. ...","B. ...","C. ...","D. ...","E. ..."],
                      "columnB": ["a. ...","b. ...","c. ...","d. ...","e. ..."],
                      "answer_key": {"A":"c","B":"e","C":"b","D":"a","E":"d"} } ],
      "essay": [ {"id":"ES1","prompt":"...", "guidance":"(2–3 short paragraphs touching X, Y, Z)"} ],
      "meta": {
        "topics_used": ["...","..."],
        "tos_check": {
          "mcq":{"easy":<n>,"avg":<n>,"hard":<n>,"total":<n>,"ok":true},
          "tf":{"easy":<n>,"avg":<n>,"hard":<n>,"total":<n>,"ok":true},
          "ident":{"easy":<n>,"avg":<n>,"hard":<n>,"total":<n>,"ok":true},
          "matching":{"groups":<g>,"pairs_per_group":<m>,"total_pairs":<t>,"ok":true},
          "essay":{"hard":<n>,"total":<n>,"ok":true}
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
  "STRICT: Meet quotas EXACTLY per type (mcq/tf/ident/matching/essay). No missing or extra items.",
  "For each set, use ONLY content relevant to that set’s 'topics' array; ignore unrelated material even if present in combined_markdown.",
  "If materials are thin, you MAY use reasonable domain knowledge to satisfy quotas.",
  "No duplicate stems within a set. MCQ has 1 correct + 3 plausible (no All/None).",
  'STRICT: Top-level MUST be a single JSON object with a "sets" array: { "sets": [ { ... }, { ... } ] }. Do not return a single set, a top-level array, or extra keys.',
  "TF unambiguous; Identification single concise answer; Matching 1–1 with answer_key; Essay prompt + brief guidance.",
  "OUTPUT STRICT JSON ONLY with the exact schema. No markdown, no prose, no code fences."
].join(" ");


  // Important: if KB is thin, allow the model to lean on topic names
  const grounding = kbIsThin
    ? "Use the provided topics as anchors and reasonable domain knowledge when the combined_markdown is brief."
    : "Prefer facts/terms from combined_markdown; ground each stem with at least one domain term found there.";

  const system = `You generate exam items aligned to a TOS but without meta talk. ${style} ${grounding}`;

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


function stripCodeFences(s=""){
  // kill ```json ... ``` or ``` ... ```
  return String(s).replace(/^\s*```(?:json)?\s*/i, "").replace(/\s*```\s*$/i, "").trim();
}
function extractFirstJSONObject(s=""){
  // grab the first {...} block to ignore any safety prelude/epilogue
  const start = s.indexOf("{");
  const end   = s.lastIndexOf("}");
  return (start >= 0 && end > start) ? s.slice(start, end+1) : s;
}



function parseJSONLoose(raw) {
  if (!raw) throw new Error("Empty response");
  let s = String(raw);

  // 0) Remove BOM & invisible/line-separator chars
  s = s.replace(/^\uFEFF/, "")
       .replace(/[\u200B-\u200D\u2060\uFEFF]/g, "")      // zero-width
       .replace(/[\u2028\u2029]/g, "\n")                 // JS line sep → \n
       .trim();

  // 1) If there are any fenced blocks anywhere, prefer the FIRST one
  //    Supports ```json / ```JSON / ```jsonc / ```json5 / ``` (any case)
  const fenceRe = /```(?:jsonc?|json5)?\s*([\s\S]*?)```/ig;
  const fenceMatch = fenceRe.exec(s);
  if (fenceMatch && fenceMatch[1]) s = fenceMatch[1].trim();

  // 2) Strip any remaining standalone fences (in case of weird placement)
  s = s.replace(/```(?:jsonc?|json5)?/ig, "").replace(/```/g, "").trim();

  // 3) Normalize curly quotes → straight
  s = s.replace(/[“”]/g, '"').replace(/[‘’]/g, "'");

  // 4) Quick direct parse
  try { return JSON.parse(s); } catch {}

  // 5) Remove comments & trailing commas (JSON5-ish)
  let cleaned = s
    .replace(/\/\/[^\n\r]*/g, "")
    .replace(/\/\*[\s\S]*?\*\//g, "")
    .replace(/,\s*([}\]])/g, "$1")
    .trim();
  try { return JSON.parse(cleaned); } catch {}

  // 6) Extract the largest balanced {...} or [...] region anywhere
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
            const chunk = str.slice(i, j + 1).trim();
            if (chunk.length > bestLen) { best = chunk; bestLen = chunk.length; }
            break;
          }
        } else if (c in pairs) {
          stack.push(c);
        }
      }
    }
    return best || null;
  }

  const largest = extractLargestBalanced(cleaned);
  if (largest) {
    // try again with trailing-comma cleanup
    const normalized = largest.replace(/,\s*([}\]])/g, "$1");

    try { return JSON.parse(normalized); } catch {}
  }

  // 7) As a last resort, slice between first { and last }
  const fi = s.indexOf("{"), la = s.lastIndexOf("}");
  if (fi !== -1 && la !== -1 && la > fi) {
    const core = s.slice(fi, la + 1).replace(/,\s*([}\]])/g, "$1");
    try { return JSON.parse(core); } catch {}
  }

  // Show only a small sample to console, then fail
  console.warn("Malformed reply sample:", s.slice(0, 1200));
  throw new Error("Batch returned non-JSON");
}




function isSetShape(o){
  if(!o || typeof o !== 'object') return false;
  // minimal shape check
  return ('set_id' in o) && (
    Array.isArray(o.mcq) || Array.isArray(o.true_false) ||
    Array.isArray(o.identification) || Array.isArray(o.matching) ||
    Array.isArray(o.essay)
  );
}

function normalizeSetsFromAny(json){
  // Already correct?
  if (json && Array.isArray(json.sets)) return json.sets;

  // Array directly?
  if (Array.isArray(json) && json.every(isSetShape)) return json;

  // Singular "set"
  if (json && isSetShape(json.set)) return [json.set];

  // Single set object at the top level
  if (json && isSetShape(json)) return [json];

  // Sometimes models wrap as { data: { sets: [...] } } or { result: [...] }
  if (json && json.data && Array.isArray(json.data.sets)) return json.data.sets;
  if (json && Array.isArray(json.result) && json.result.every(isSetShape)) return json.result;

  return null; // unknown shape
}

// Safer no-op normalizer for arrays on a set
function ensureSetArrays(s){
  s.mcq = Array.isArray(s.mcq) ? s.mcq : [];
  s.true_false = Array.isArray(s.true_false) ? s.true_false : [];
  s.identification = Array.isArray(s.identification) ? s.identification : [];
  s.matching = Array.isArray(s.matching) ? s.matching : [];
  s.essay = Array.isArray(s.essay) ? s.essay : [];
  return s;
}

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

// === Define the missing batch caller ===
// Depends on: AGENTS, apikey, scheduledJsonFetch, makeBatchMessages_AI,
// parseJSONLoose, normalizeSetsFromAny, salvageSetsFromRaw,
// ensureSetArrays, callTopUp, computeDeficits
async function callAgentForBatch(agentIdx, kb, plansChunk){
  const lane = AGENTS[agentIdx % AGENTS.length];

  // 1) Ask the model for all sets in this chunk
  const payload = {
    model: lane.model,
    agent: lane.agent,
    temperature: 0.2,
    response_format: { type: "json_object" },
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

  // 2) Parse JSON (tolerant) or salvage individual set objects
  let json = null, sets = null;
  try { json = parseJSONLoose(raw); } catch {}
  if (json) sets = normalizeSetsFromAny(json);
  if (!sets) {
    const salvaged = salvageSetsFromRaw(raw);
    if (salvaged && salvaged.length) sets = salvaged;
  }
  if (!sets) {
    console.warn("Unexpected batch shape; raw sample:", raw.slice(0, 400));
    throw new Error("Malformed batch JSON");
  }

  // 3) Index by set_id and ensure array fields exist
  const got = new Map(sets.map(s => {
    const clean = ensureSetArrays(s || {});
    return [clean.set_id, clean];
  }));

  // 4) For every plan in this chunk, make sure we have a set and top-up deficits
  const normalized = [];
  for (const plan of plansChunk){
    let s = got.get(plan.set_id) || ensureSetArrays({ set_id: plan.set_id, meta:{} });

    // fill any missing items, a few passes max
    for (let pass = 0; pass < 8; pass++){
      const d = computeDeficits(s, plan.quotas);
      // const need = d.mcq || d.tf || d.ident || d.essay || d.matchGroups;
      const need = d.mcq || d.tf || d.ident || d.essay || d.matchingGroups;
      if (!need) break;
      s = await callTopUp(agentIdx, kb, plan, s);
    }

    // sanity trim (if model overshot)
    const trim = (arr, want) => Array.isArray(arr) && arr.length > want ? arr.slice(0, want) : arr;
    s.mcq            = trim(s.mcq,            plan.quotas?.mcq?.total|0);
    s.true_false     = trim(s.true_false,     plan.quotas?.tf?.total|0);
    s.identification = trim(s.identification, plan.quotas?.ident?.total|0);
    s.essay          = trim(s.essay,          plan.quotas?.essay?.total|0);
    s.matching       = trim(s.matching,       plan.quotas?.matching?.groups|0);

    normalized.push(s);
  }

  return normalized;
}


async function runBatchesParallel(kb, allPlans){
  const batches = chunkPlans(allPlans, BATCH_SIZE);
  const results = [];
  let nextBatch = 0;

  const workers = Array.from({ length: Math.min(AGENTS.length, batches.length) }, (_, workerIdx) => (async () => {
    while (true) {
      const myBatchIdx = nextBatch++;
      if (myBatchIdx >= batches.length) break;

      const plansChunk = batches[myBatchIdx];
      try {
        const part = await callAgentForBatch(workerIdx, kb, plansChunk); // workerIdx picks AGENT[workerIdx]
        results.push(...part);
      } catch (err) {
        console.warn("Worker", workerIdx, "retrying batch", myBatchIdx, err);
        await new Promise(r => setTimeout(r, 3000));
        const part = await callAgentForBatch(workerIdx, kb, plansChunk);
        results.push(...part);
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
  const sections = { MCQ:[], TF:[], ID:[], MATCH:[], ESSAY:[] };
  const keyLines = [];
  let qnum = 1;
  const figAlloc = makeFigureAllocator(figs);
  
  const pushKey = (n, ans) => keyLines.push(`${n}. ${ans}`);

  // Insert a figure only for allowed types; supports [FIG] placeholder
function stemWithInlineFigureFor(type, rawStem, fallbackAlt = 'Figure') {
  const stemText = String(rawStem ?? '');
  const allowed = (type === 'mcq' && FIG_USE.mcq) || (type === 'tf' && FIG_USE.tf);
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

  // True/False
  (set.true_false||[]).forEach(it=>{
    // const stem = stemWithInlineFigure(it.stem, `Figure ${qnum}`);
    const stem = stemWithInlineFigureFor('tf', it.stem, `Figure ${qnum}`);
    sections.TF.push(
      `<div class="q">
         <div><strong>${qnum}.</strong> ${stem} (True/False)</div>
       </div>`
    );
    pushKey(qnum, it.answer || "");
    qnum++;
  });
/*
  // Identification
  (set.identification||[]).forEach(it=>{
    const stem = stemWithInlineFigure(it.prompt, `Figure ${qnum}`);
    sections.ID.push(
      `<div class="q"><strong>${qnum}.</strong> ${stem} <em>(Identification)</em></div>`
    );
    pushKey(qnum, it.answer || "");
    qnum++;
  });
*/

// Identification
(set.identification||[]).forEach(it=>{
  const stem = escapeHTML(it.prompt ?? '');
  sections.ID.push(
    `<div class="q"><strong>${qnum}.</strong> ${stem} <em>(Identification)</em></div>`
  );
  pushKey(qnum, it.answer || "");
  qnum++;
});


  // Matching — COUNT EACH PAIR AS ONE NUMBERED ITEM
  (set.matching || []).forEach((group, gidx) => {
    const colA = Array.isArray(group?.columnA) ? group.columnA.slice() : [];
    const colB = Array.isArray(group?.columnB) ? group.columnB.slice() : [];
    const pairs = Math.min(colA.length, colB.length);
    if (!pairs) return;

    const stripLead = s => String(s ?? "").replace(/^\s*([0-9]+\.|[A-Za-z]\.)\s*/, "").trim();
    const letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ".split("");

    // For Matching we keep stems pure; if you want an inline fig before the table, uncomment:
    // const preFig = figureImgHTML(nextFig(), `Figure ${qnum}`);

    const colBLines = colB.slice(0, pairs).map((txt, i) => {
      const L = letters[i] || String.fromCharCode(65 + (i % 26));
      return `${L}. ${escapeHTML(stripLead(txt))}`;
    });

    const aLinesHTML = colA.slice(0, pairs).map((txt, i) =>
      `${(qnum + i)}. ${escapeHTML(stripLead(txt))}`
    ).join("<br>");

    const bLinesHTML = colBLines.join("<br>");

    sections.MATCH.push(
      `<div class="q">
         ${ (set.matching?.length > 1) ? `<div class="small muted mb-1">Group ${gidx+1}</div>` : "" }
         <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:6px;">
           <div><div class="small muted">Column A</div>${aLinesHTML}</div>
           <div><div class="small muted">Column B</div>${bLinesHTML}</div>
         </div>
       </div>`
    );

    const keyMap = group?.answer_key || {};
    for (let i = 0; i < pairs; i++) {
      let ans = keyMap[String(i + 1)];
      if (!ans) {
        const k = letters[i];
        ans = keyMap[k] || keyMap[k?.toLowerCase()];
      }
      pushKey(qnum + i, (ans || "").toString().trim());
    }
    qnum += pairs;
  });
/*
  // Essay
  (set.essay||[]).forEach(it=>{
    const stem = stemWithInlineFigure((it.prompt + (it.guidance ? ` ${it.guidance}` : "")), `Figure ${qnum}`);
    sections.ESSAY.push(
      `<div class="q"><strong>${qnum}.</strong> ${stem} <em>(Essay)</em></div>`
    );
    pushKey(qnum, "(open-ended)");
    qnum++;
  });
*/

// Essay
(set.essay||[]).forEach(it=>{
  const stem = escapeHTML((it.prompt || '') + (it.guidance ? ` ${it.guidance}` : ""));
  sections.ESSAY.push(
    `<div class="q"><strong>${qnum}.</strong> ${stem} <em>(Essay)</em></div>`
  );
  pushKey(qnum, "(open-ended)");
  qnum++;
});


  // Build the paper HTML (no header figure — all figures are inline now)
  const examHtml = `
    <div class="paper" contenteditable="true" data-paper>
      <header class="flex items-end justify-between gap-4 pb-3 mb-2 border-b border-gray-200">
        <div class="title">${escapeHTML(title)} — SET ${num}</div>
        <div class="small muted">Name: ____________________  Score: ______</div>
      </header>
      ${ sections.MCQ.length   ? `<div class="font-semibold mt-4 mb-1">Multiple Choice</div>${sections.MCQ.join('')}`   : '' }
      ${ sections.TF.length    ? `<div class="font-semibold mt-4 mb-1">True or False</div>${sections.TF.join('')}`       : '' }
      ${ sections.ID.length    ? `<div class="font-semibold mt-4 mb-1">Identification</div>${sections.ID.join('')}`       : '' }
      ${ sections.MATCH.length ? `<div class="font-semibold mt-4 mb-1">Matching Type</div>${sections.MATCH.join('')}`     : '' }
      ${ sections.ESSAY.length ? `<div class="font-semibold mt-4 mb-1">Essay</div>${sections.ESSAY.join('')}`             : '' }
      <footer class="mt-6 pt-3 border-t border-dashed border-gray-200 small muted">Generated by Exam Maker - Agents</footer>
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

async function buildRowsMeta_AI(chosenTypes){
  const cards = $$('#rows > div');
  const metas = await Promise.all(cards.map(async (card)=>{
    const id=card.dataset.rowId;
    const topic=card.querySelector('.topicInput')?.value || "(Untitled)";
    const rawMd = await getRowMarkdownFromCard(card); // no cleaning
    const figsChosen = getChosenFiguresForCard(card);
    return { id, topic, raw_markdown: rawMd || "", figures: figsChosen, cells:{
      r: card.querySelector('.r')?.value || '',
      u: card.querySelector('.u')?.value || '',
      ap: card.querySelector('.ap')?.value || '',
      an: card.querySelector('.an')?.value || '',
      cr: card.querySelector('.cr')?.value || '',
      ev: card.querySelector('.ev')?.value || ''
    }};
  }));
  return metas;
}

async function generateAI(){
  const types={ mcq:false, tf:false, id:false, match:false, essay:false };
  $$('.examType').forEach(cb=> types[cb.value]=cb.checked);
  if(!Object.values(types).some(Boolean)) return showAlert('err','Select at least one exam type.');
  const cards = $$('#rows > div'); if(!cards.length) return showAlert('err','Add at least one TOS row.');

  const { rows, numMap } = buildTOS();
  const allNums = Array.from(new Set(Object.keys(numMap).map(n=>+n))).sort((a,b)=>a-b);
  if(!allNums.length) return showAlert('err','Provide TOS numbers (e.g., 1,2,3…) in the cells.');

  const sets = Math.max(1, Math.min(MAX_SETS, parseInt(byId('numSets').value||'1',10)));

  setLoader('Reading materials...','Converting files and preparing AI context'); setProgress(10,'Files');
  const rowsMeta = await buildRowsMeta_AI(types);
  
  // gather all selected figures across rows
    const selectedFigs = rowsMeta.flatMap(r => r.figures || []);
    const figsData = await normalizeFiguresToData(selectedFigs);
    const title = byId('examTitle').value||'Exam';
  
  try{
    setProgress(45,'KB build');
    const kb = makeKBFromRows_AI(rowsMeta);
    setProgress(55,'Planning sets');
    const plans = buildSetPlansFromTOS(rowsMeta, types, sets, numMap);
    setProgress(60,'Calling agents');
    
    const chosenFigs = getAllSelectedFigures();  // NEW
    
  
    let aiSets = await runBatchesParallel(kb, plans);
    aiSets = await ensureAllSetsFilled(aiSets, plans, kb, 0);

    
    setProgress(85,'Render');
    const built = aiSets.map(s => buildHTMLFromAISchemaSet(s, title, figsData /*selectedFigs 0970 */ ));

    byId('sets').innerHTML = '';
    renderSets(built);

    lastCompiledSets = built;
    const compiled = buildPlaintextFromSets(built, title);
    lastCompiledText = compiled.text;
    lastTotalQuestions = compiled.totalQ;
    byId('compiledText').value = lastCompiledText;

    hideLoader();
    showAlert('ok', `Draft(s) ready via Agents. Total questions: ${lastTotalQuestions}. You can save.`);
    byId('btnSaveDbTop').disabled = false;
    byId('btnSaveDbTop').removeAttribute('title');
  }catch(e){
    console.error(e);
    hideLoader();
    showAlert('err','Agent generation failed.');
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

/*
(function(){
  const $g = (id)=>document.getElementById(id);
  const bind = (id, fn)=>{ const el = $g(id); if (el) el.addEventListener('click', fn); };
  const title = ()=> (document.getElementById('examTitle')?.value || 'exam');
  bind('dlDocxExam', ()=> downloadDOCX(`${title()}-sets`, getExamOnlyHTML()));
  bind('dlPdfExam',  ()=> downloadPDF (`${title()}-sets`, getExamOnlyHTML()));
  bind('dlDocxKey',  ()=> downloadDOCX(`${title()}-keys`, getCompiledKeysHTML()));
  bind('dlPdfKey',   ()=> downloadPDF (`${title()}-keys`, getCompiledKeysHTML()));
})();
*/

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

if (byId('btnGenerate')) byId('btnGenerate').onclick = generateAI;


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
  const names = $$('#rows > div').map((c)=> {
    const id=c.dataset.rowId; return (rowState[id]?.files||[]).map(f=>f.name).join('; ');
  }).join(' | ');

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
