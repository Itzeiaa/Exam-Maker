<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Exam Maker - View Exam</title>
  @vite('resources/css/app.css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>

  <!-- ----- Export libs (order matters) ----- -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/html-to-pdfmake@2.4.5/browser.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/3.0.4/purify.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/html-docx-js/dist/html-docx.js"></script>
</head>
<body class="min-h-screen relative overflow-x-hidden bg-gray-50">
  <div class="absolute inset-0 gradient-animated pointer-events-none"></div>

  <script>
    const jwt = localStorage.getItem('jwt_token');
    if (!jwt) location.replace('/login');
  </script>

   <div class="flex relative z-10 w-full min-h-screen overflow-y-auto">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-2xl min-h-screen border-r border-gray-200 relative">
      <a href="/dashboard" class="flex items-center p-6 border-b border-gray-100 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 transition-all duration-200">
        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mr-3 shadow-lg">
          <img style="width:30px" src="/images/icon.png"></img>
        </div>
        <h1 class="text-xl font-bold text-white">Exam Maker</h1>
      </a>

      <nav class="mt-6 px-4">
        <a href="/dashboard" class="flex items-center px-4 py-3 text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all duration-200 mb-2 group">
          <i class="fas fa-tachometer-alt mr-3 group-hover:scale-110 transition-transform duration-200"></i>
          Dashboard
        </a>
        <a href="/generate-exam" class="flex items-center px-4 py-3 text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all duration-200 mb-2 group">
          <i class="fas fa-plus mr-3 group-hover:scale-110 transition-transform duration-200"></i>
          Generate Exam
        </a>
        <a href="/my-exams" class="flex items-center px-4 py-3 text-white bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg mb-2 transform hover:scale-105 transition-all duration-200">
          <i class="fas fa-file-alt mr-3"></i>
          My Exams
        </a>
        <a href="/profile" class="flex items-center px-4 py-3 text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all duration-200 mb-2 group">
          <i class="fas fa-user mr-3 group-hover:scale-110 transition-transform duration-200"></i>
          Profile
        </a>
      </nav>

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
        <button id="logoutBtn" class="w-full bg-white text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-100 border border-gray-200 shadow-sm transition-all duration-200 hover:shadow-md">
          Logout
        </button>
      </div>
    </aside>

    <!-- Main -->
    <main class="flex-1 p-8 min-h-screen overflow-y-auto">
      <!-- Header -->
      <div class="mb-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Exam Details</h1>
            <p class="text-gray-600">View, edit, export, and save your exam.</p>
          </div>
          <div class="flex flex-wrap gap-4">
            <button id="btnDocx" class="bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl font-medium inline-flex items-center transform hover:scale-105 active:scale-95" style="background-color: #2563eb !important; color: white !important;">
              <i class="fas fa-file-word mr-2"></i> Download Exam
            </button>
            <button id="btnPdfKeys" class="bg-amber-600 text-white px-4 py-3 rounded-lg hover:bg-amber-700 transition-all duration-200 shadow-lg hover:shadow-xl font-medium inline-flex items-center transform hover:scale-105 active:scale-95">
              <i class="fas fa-key mr-2"></i> Download PDF (Answer Key Only)
            </button>

            <button id="btnSave" class="bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 transition-all duration-200 shadow-lg hover:shadow-xl font-medium inline-flex items-center transform hover:scale-105 active:scale-95" style="background-color: #16a34a !important; color: white !important;">
              <i class="fas fa-save mr-2"></i> Save Changes
            </button>
            <a href="/my-exams" class="bg-gray-500 text-white px-4 py-3 rounded-lg hover:bg-gray-600 transition-all duration-200 shadow-lg hover:shadow-xl font-medium inline-flex items-center transform hover:scale-105 active:scale-95">
              <i class="fas fa-arrow-left mr-2"></i> Back to Exams
            </a>
          </div>
        </div>
      </div>

      <!-- Exam Info -->
      <section class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
          <div class="flex items-center justify-between">
            <div>
              <!-- Make title and description editable inline -->
              <h2 id="examTitle" class="text-2xl font-bold mb-1 outline-none" contenteditable="true">Loading…</h2>
              <p id="examDesc" class="text-blue-100 outline-none" contenteditable="true">Please wait</p>
              <p class="text-xs opacity-80 mt-1">Tip: You can edit the title and description above.</p>
            </div>
            <div class="text-right">
              <span id="examStatus" class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm font-medium text-gray-900">—</span>
            </div>
          </div>
        </div>

        <div class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="text-center p-4 bg-blue-50 rounded-lg">
              <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-file-alt text-white text-lg"></i>
              </div>
              <h3 class="font-semibold text-gray-900 mb-1">Exam Type</h3>
              <p id="examType" class="text-gray-600">—</p>
            </div>

            <div class="text-center p-4 bg-green-50 rounded-lg">
              <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-question-circle text-white text-lg"></i>
              </div>
              <h3 class="font-semibold text-gray-900 mb-1">Questions</h3>
              <p id="examQuestions" class="text-gray-600">0</p>
              <p id="examDetected" class="text-xs text-gray-500 mt-1"></p>
            </div>

            <div class="text-center p-4 bg-purple-50 rounded-lg">
              <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-copy text-white text-lg"></i>
              </div>
              <h3 class="font-semibold text-gray-900 mb-1">Exam Sets</h3>
              <p id="examSets" class="text-gray-600">0</p>
            </div>
          </div>

          <div class="flex items-center justify-between text-sm text-gray-500">
            <div class="flex items-center">
              <i class="fas fa-clock mr-2"></i>
              Created: <span id="createdAt">—</span>
            </div>
            <div class="flex items-center">
              <i class="fas fa-upload mr-2"></i>
              Last updated: <span id="updatedAt">—</span>
            </div>
          </div>
        </div>
      </section>
      
      <!-- Editor Toolbar -->
        <div class="editor-toolbar bg-white rounded-xl shadow-md border border-gray-100 p-3 mb-3">
          <div class="flex flex-wrap gap-2">
            <button data-cmd="bold"><i class="fas fa-bold"></i></button>
            <button data-cmd="italic"><i class="fas fa-italic"></i></button>
            <button data-cmd="underline"><i class="fas fa-underline"></i></button>
            <button data-cmd="insertUnorderedList"><i class="fas fa-list-ul"></i></button>
            <button data-cmd="insertOrderedList"><i class="fas fa-list-ol"></i></button>
            <button data-align="left"><i class="fas fa-align-left"></i></button>
            <button data-align="center"><i class="fas fa-align-center"></i></button>
            <button data-align="right"><i class="fas fa-align-right"></i></button>
            <select id="fontSizeSel">
              <option value="">Font Size</option>
              <option value="12px">12</option>
              <option value="14px">14</option>
              <option value="16px">16</option>
              <option value="18px">18</option>
              <option value="24px">24</option>
              <option value="32px">32</option>
            </select>
          </div>
        </div>

      <!-- Rendered/Editable Paper -->
      <section class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 border-b border-gray-200">
          <h3 class="text-xl font-bold text-gray-900">Exam Paper (Editable)</h3>
          <p class="text-gray-600 mt-1">Edit directly below. Changes are local until you click “Save Changes”.</p>
        </div>

        <div class="p-6">
          <div id="paper" class="prose max-w-none outline-none border border-gray-200 rounded-lg p-5 min-h-[600px] bg-white" contenteditable="true">
            <div class="bg-gray-50 text-gray-600 rounded p-4">Loading paper…</div>
          </div>
          <div id="saveNote" class="text-xs text-gray-500 mt-2">Unsaved</div>
        </div>
      </section>
      
      
    </main>
    
    
    <!-- ===== Export Header Modal (drop-in) ===== -->
<div id="exportHeaderModal" class="fixed inset-0 z-[99999] hidden">
  <!-- backdrop -->
  <div class="absolute inset-0 bg-black/60"></div>

  <!-- panel -->
  <div class="absolute inset-0 p-4 md:p-8 flex items-start md:items-center justify-center overflow-y-auto">
    <div class="w-full max-w-3xl bg-white rounded-2xl shadow-2xl ring-1 ring-black/5 pointer-events-auto relative">
      <!-- top bar -->
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
        <div class="flex items-center gap-3">
          <div class="size-9 rounded-xl bg-blue-600/10 text-blue-700 grid place-items-center">
            <i class="fa-solid fa-school text-sm"></i>
          </div>
          <div>
            <div class="text-lg font-semibold text-gray-900">Export Header</div>
            <div class="text-xs text-gray-500">Add your school logos, name, and address (optional)</div>
          </div>
        </div>
        <button id="eh_close" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500">
          <i class="fa-solid fa-xmark text-lg"></i>
        </button>
      </div>

      <!-- body -->
      <div class="px-6 py-5">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 items-start">
          <!-- Left logo card -->
          <div class="rounded-xl border border-gray-200 p-4">
            <div class="text-sm font-medium mb-2">Left Logo</div>
            <label class="block">
              <input id="eh_leftFile" type="file" accept="image/*" class="hidden">
              <div id="eh_leftDrop"
                   class="aspect-square rounded-xl border-2 border-dashed border-gray-300 grid place-items-center text-gray-400 hover:border-blue-400 hover:text-blue-500 cursor-pointer relative">
                <div id="eh_leftEmpty" class="text-center text-xs px-2">
                  <i class="fa-regular fa-image mb-2 block text-xl"></i>
                  Click to upload
                </div>
                <img id="eh_leftPreview" class="hidden object-contain w-full h-full p-2 rounded-xl" alt="Left logo">
                <button id="eh_leftRemove"
                        class="hidden absolute -right-2 -top-2 size-7 rounded-full bg-white shadow ring-1 ring-gray-200 text-gray-600 hover:bg-gray-50"
                        title="Remove">
                  <i class="fa-solid fa-xmark"></i>
                </button>
              </div>
            </label>
            <div class="mt-3">
              <label class="block text-xs text-gray-600 mb-1">Width (px)</label>
              <input id="eh_leftW" type="number" value="90" min="30" max="240"
                     class="border rounded-lg px-2 py-1 w-28">
            </div>
          </div>

          <!-- Center text -->
          <div class="rounded-xl border border-gray-200 p-4 md:col-span-1">
            <div class="text-sm font-medium mb-2">School Header Text</div>
            <input id="eh_title" type="text"
                   placeholder="Bulacan State University — Main Campus"
                   class="border rounded-lg px-3 py-2 w-full mb-2">
            <input id="eh_college" type="text"
                   placeholder="College of Information and Communication Technology"
                   class="border rounded-lg px-3 py-2 w-full mb-2">
            <input id="eh_addr" type="text"
                   placeholder="Guinhawa, City of Malolos, Bulacan"
                   class="border rounded-lg px-3 py-2 w-full">
            <p class="text-[11px] text-gray-500 mt-2">If only text is provided, the header will center it without logos.</p>
          </div>

          <!-- Right logo card -->
          <div class="rounded-xl border border-gray-200 p-4">
            <div class="text-sm font-medium mb-2">Right Logo</div>
            <label class="block">
              <input id="eh_rightFile" type="file" accept="image/*" class="hidden">
              <div id="eh_rightDrop"
                   class="aspect-square rounded-xl border-2 border-dashed border-gray-300 grid place-items-center text-gray-400 hover:border-blue-400 hover:text-blue-500 cursor-pointer relative">
                <div id="eh_rightEmpty" class="text-center text-xs px-2">
                  <i class="fa-regular fa-image mb-2 block text-xl"></i>
                  Click to upload
                </div>
                <img id="eh_rightPreview" class="hidden object-contain w-full h-full p-2 rounded-xl" alt="Right logo">
                <button id="eh_rightRemove"
                        class="hidden absolute -right-2 -top-2 size-7 rounded-full bg-white shadow ring-1 ring-gray-200 text-gray-600 hover:bg-gray-50"
                        title="Remove">
                  <i class="fa-solid fa-xmark"></i>
                </button>
              </div>
            </label>
            <div class="mt-3">
              <label class="block text-xs text-gray-600 mb-1">Width (px)</label>
              <input id="eh_rightW" type="number" value="90" min="30" max="240"
                     class="border rounded-lg px-2 py-1 w-28">
            </div>
          </div>
        </div>
      </div>

      <!-- footer actions -->
      <div class="px-6 py-4 border-t border-gray-100 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3 bg-gray-50">
        <div class="text-[11px] text-gray-500">You can leave any field empty. We’ll still export.</div>
        <div class="flex gap-2">
          <button style="background-color: #2563eb !important; color: white !important;margin-right: 10px;" id="eh_ok_docx" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 shadow-sm">
            <i class="fa-solid fa-file-word mr-2"></i>Download DOCX
          </button>
          <button style="background-color: #dc2626 !important; color: white !important;" id="eh_ok_pdf" class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 shadow-sm">
            <i class="fa-solid fa-file-pdf mr-2"></i>Download PDF
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
  </div>


    <style>
      .editor-toolbar {
        --et-bg: #ffffff;
        --et-border: #e5e7eb;     /* gray-200 */
        --et-shadow: 0 6px 18px -8px rgba(0,0,0,.15);
        --et-hover: #f8fafc;      /* gray-50 */
        --et-press: #f1f5f9;      /* gray-100 */
        --et-accent: #3b82f6;     /* blue-500 */
        --et-accent-600: #2563eb; /* blue-600 */
        --et-text: #0f172a;       /* slate-900 */
      }
    
      /* Base button look */
      .editor-toolbar button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .5rem;
        padding: .55rem .8rem;
        border-radius: .75rem;
        border: 1px solid var(--et-border);
        background: linear-gradient(180deg, #fff, #f9fafb 80%, #f3f4f6);
        box-shadow: 0 1px 0 rgba(255,255,255,.8) inset, var(--et-shadow);
        color: var(--et-text);
        transition: background .18s ease, transform .05s ease, border-color .18s ease, box-shadow .18s ease;
        cursor: pointer;
      }
    
      .editor-toolbar button i { font-size: .95rem; }
    
      .editor-toolbar button:hover {
        background: var(--et-hover);
        border-color: #d1d5db; /* gray-300 */
      }
    
      .editor-toolbar button:active {
        transform: translateY(1px);
        background: var(--et-press);
      }
    
      .editor-toolbar button:focus-visible {
        outline: 0;
        box-shadow: 0 0 0 3px rgba(59,130,246,.25), var(--et-shadow);
      }
    
      /* Active/toggled state (apply .is-active or aria-pressed="true") */
      .editor-toolbar button.is-active,
      .editor-toolbar button[aria-pressed="true"] {
        background: linear-gradient(180deg, var(--et-accent), var(--et-accent-600));
        border-color: var(--et-accent-600);
        color: #fff;
        box-shadow: 0 1px 0 rgba(255,255,255,.15) inset, 0 10px 20px -10px rgba(37,99,235,.65);
      }
    
      /* Group alignment buttons nicely if you want to wrap them (optional) */
      .editor-toolbar .align-group {
        display: inline-flex;
        border: 1px solid var(--et-border);
        border-radius: .75rem;
        overflow: hidden;
        box-shadow: var(--et-shadow);
        background: #fff;
      }
      .editor-toolbar .align-group button {
        border: 0;
        border-right: 1px solid var(--et-border);
        border-radius: 0;
      }
      .editor-toolbar .align-group button:last-child { border-right: 0; }
    
      /* Select (custom “chip” style) */
      .editor-toolbar select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
    
        padding: .55rem 2.25rem .55rem .8rem;
        border-radius: .75rem;
        border: 1px solid var(--et-border);
        background:
          linear-gradient(180deg, #fff, #f9fafb 80%, #f3f4f6),
          /* arrow */
          url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 20 20' fill='none' stroke='%236b7280' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 8 10 12 14 8'/></svg>") no-repeat right .7rem center / 14px 14px;
        color: var(--et-text);
        box-shadow: var(--et-shadow);
        transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
      }
      .editor-toolbar select:hover  { border-color: #d1d5db; }
      .editor-toolbar select:focus-visible {
        outline: 0;
        box-shadow: 0 0 0 3px rgba(59,130,246,.25), var(--et-shadow);
      }
    
      /* Compact on very small screens */
      @media (max-width: 420px) {
        .editor-toolbar button { padding: .48rem .65rem; border-radius: .65rem; }
        .editor-toolbar select { padding: .48rem 2.1rem .48rem .65rem; border-radius: .65rem; }
        .editor-toolbar button i { font-size: .9rem; }
      }

    @keyframes gradientShift { 0% { background-position: 0% 50% } 50% { background-position: 100% 50% } 100% { background-position: 0% 50% } }
    .gradient-animated { background: linear-gradient(-45deg,#1e3a8a,#3b82f6,#60a5fa,#93c5fd,#1e40af,#1d4ed8); background-size: 400% 400%; animation: gradientShift 15s ease infinite; }
    .prose { font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Arial; line-height: 1.6; }
    .prose h1,.prose h2,.prose h3{ font-weight:700; margin-top: 1rem; margin-bottom: .5rem; }
    .prose ol, .prose ul { padding-left: 1.25rem; }
    .prose .page-break { page-break-before: always; }
    #paper:focus { box-shadow: 0 0 0 3px rgba(59,130,246,.25); }
  </style>

  <script>
      function getCurrentHeaderCfg() {
  const $ = (id)=>document.getElementById(id);
  return {
    title:  ($('eh_title')?.value || '').trim(),
    college:($('eh_college')?.value || '').trim(),
    addr:   ($('eh_addr')?.value || '').trim(),
    left:  { src: $('eh_leftPreview')?.dataset.dataurl || '',  w: +($('eh_leftW')?.value||0) || 0 },
    right: { src: $('eh_rightPreview')?.dataset.dataurl || '', w: +($('eh_rightW')?.value||0) || 0 }
  };
}


  // Open the modal: openExportHeaderModal('docx'|'pdf'|'skip')
  function openExportHeaderModal(target='docx'){
    const m = document.getElementById('exportHeaderModal');
    m.classList.remove('hidden');
    // focus the close button for a11y
    document.getElementById('eh_close').focus();
  }
  function closeExportHeaderModal(){
    document.getElementById('exportHeaderModal').classList.add('hidden');
  }

  // Make the whole dropzone clickable
  const lDrop = document.getElementById('eh_leftDrop');
  const rDrop = document.getElementById('eh_rightDrop');
  // lDrop.addEventListener('click', ()=> document.getElementById('eh_leftFile').click());
  // rDrop.addEventListener('click', ()=> document.getElementById('eh_rightFile').click());

  // Helpers: show preview + remove
  function wirePreview(side){
    const file   = document.getElementById(`eh_${side}File`);
    const prev   = document.getElementById(`eh_${side}Preview`);
    const empty  = document.getElementById(`eh_${side}Empty`);
    const remove = document.getElementById(`eh_${side}Remove`);

    file.addEventListener('change', async () => {
      const f = file.files && file.files[0];
      if (!f) return;
      const url = await fileToDataUrl(f);
      file.value = '';
      prev.src = url;
      prev.classList.remove('hidden');
      empty.classList.add('hidden');
      remove.classList.remove('hidden');
      prev.dataset.dataurl = url; // stash for export
    });
/*
    remove.addEventListener('click', (e) => {
      e.stopPropagation();
      file.value = '';
      prev.removeAttribute('src');
      prev.dataset.dataurl = '';
      prev.classList.add('hidden');
      remove.classList.add('hidden');
      empty.classList.remove('hidden');
    });
    
    
    remove.addEventListener('click', (e) => {
  e.stopPropagation();

  // Reset input + preview
  file.value = '';
  prev.removeAttribute('src');
  prev.dataset.dataurl = '';
  prev.classList.add('hidden');
  remove.classList.add('hidden');
  empty.classList.remove('hidden');

  // 🧹 CLEAR from global state too
  if (window.exportHeaderState && window.exportHeaderState[side]) {
    window.exportHeaderState[side].src = '';
    window.exportHeaderState[side].w = 0;
  }
});
*/
    
    remove.addEventListener('click', (e) => {
  e.stopPropagation();

  // clear UI
  file.value = '';
  prev.removeAttribute('src');
  prev.dataset.dataurl = '';
  prev.classList.add('hidden');
  remove.classList.add('hidden');
  empty.classList.remove('hidden');

  // clear any cached state
  if (window.exportHeaderState && window.exportHeaderState[side]) {
    window.exportHeaderState[side].src = '';
    window.exportHeaderState[side].w   = 0;
  }
  window._exportHeaderCfg = null; // nuke stale cache if it existed
});



  }
  wirePreview('left');
  wirePreview('right');

  async function fileToDataUrl(f){
    return await new Promise((res,reject)=>{
      const r = new FileReader();
      r.onload = () => res(String(r.result||'')); r.onerror = reject; r.readAsDataURL(f);
    });
  }

  // Collect config to a global used by your exporters
  function readHeaderForm(){
    return {
      title:  document.getElementById('eh_title').value.trim(),
      addr:   document.getElementById('eh_addr').value.trim(),
      left: {
        src:  document.getElementById('eh_leftPreview').dataset.dataurl || '',
        wpx:  Number(document.getElementById('eh_leftW').value||0) || 0
      },
      right: {
        src:  document.getElementById('eh_rightPreview').dataset.dataurl || '',
        wpx:  Number(document.getElementById('eh_rightW').value||0) || 0
      }
    };
  }

  // Buttons
  document.getElementById('eh_close').onclick = closeExportHeaderModal;
  
  document.getElementById('eh_ok_docx').onclick = () => {
    window._exportHeaderCfg = readHeaderForm();
    closeExportHeaderModal();
    triggerExport('docx');
  };
  document.getElementById('eh_ok_pdf').onclick  = () => {
    window._exportHeaderCfg = readHeaderForm();
    closeExportHeaderModal();
    triggerExport('pdf');
  };

  // Hook your toolbar buttons to open the modal instead of exporting immediately
  document.getElementById('btnDocx').onclick = ()=> openExportHeaderModal('docx');

  function triggerExport(kind){
    const title = (document.getElementById('examTitle').innerText || 'Exam Paper').trim();
    if (kind === 'docx') return downloadDOCX(title);   // your existing function
    if (kind === 'pdf')  return downloadPDF(title);    // your existing function
  }

  // Optional: close on ESC / backdrop click
  document.getElementById('exportHeaderModal').addEventListener('click', (e)=>{
    if (e.target === e.currentTarget || e.target === e.currentTarget.firstElementChild) closeExportHeaderModal();
  });
  window.addEventListener('keydown', (e)=>{ if(e.key==='Escape') closeExportHeaderModal(); });

function makeHeaderHTML(cfg){
  if (!cfg) return '';
  const img = (s,w)=> s ? `<img src="${s}" style="width:${w||90}px;height:auto;object-fit:contain;">` : '';
  const title = cfg.title ? `<div style="font-weight:700;font-size:16pt">${escapeHTML(cfg.title)}</div>` : '';
  const addr  = cfg.addr  ? `<div style="font-size:11pt;color:#444">${escapeHTML(cfg.addr)}</div>` : '';
  return `
  <table style="width:100%;border-collapse:collapse;margin:0 0 10pt 0">
    <tr>
      <td style="width:25%;text-align:left;vertical-align:middle">${img(cfg.left?.src,  cfg.left?.w)}</td>
      <td style="width:50%;text-align:center;vertical-align:middle">${title}${addr}</td>
      <td style="width:25%;text-align:right;vertical-align:middle">${img(cfg.right?.src, cfg.right?.w)}</td>
    </tr>
  </table>`;
}

      
     
    /* --------- config --------- */
    const TOKEN_KEY   = 'jwt_token';
    const CACHE_KEY   = 'profile_cache';
    const DEFAULT_AVATAR = '/images/default-avatar.png';

    const api = {
      examShow:     '/api/exam_show.php',
      examDownload: '/api/exam_download.php',   // (kept) if you still need server .doc
      examUpdate:   '/api/exam_update.php',     // NEW: to save edited HTML/title/desc
      me:           '/api/me.php',
    };

    /* --------- utils --------- */
    const $ = (id) => document.getElementById(id);
    const RAW_JWT = (localStorage.getItem(TOKEN_KEY) || '').replace(/^"|"$/g,'').replace(/^Bearer\s+/i,'');
    if (!RAW_JWT) location.replace('/login');

    function bearerHeaders(extra = {}) {
      const t = (localStorage.getItem(TOKEN_KEY) || '').replace(/^"|"$/g,'').replace(/^Bearer\s+/i,'');
      return { 'Authorization': 'Bearer ' + t, 'Accept': 'application/json', ...extra };
    }
    function parseJwt(t){
      try{
        const [,p]=t.split('.'); if(!p) return {};
        const b=p.replace(/-/g,'+').replace(/_/g,'/');
        const json=decodeURIComponent(atob(b).split('').map(c => '%'+('00'+c.charCodeAt(0).toString(16)).slice(-2)).join(''));
        return JSON.parse(json);
      }catch{ return {}; }
    }
    (function expiryGuard(){
      const payload = parseJwt(RAW_JWT);
      if (payload?.exp && Date.now() >= payload.exp * 1000) {
        localStorage.removeItem(TOKEN_KEY);
        localStorage.removeItem(CACHE_KEY);
        location.replace('/login');
      }
    })();

    async function getJSONWithRetry(url, {params = {}, method='GET', body=null, retries = 5, backoffMs = 600, headersExtra={}} = {}) {
      const u = new URL(url, location.origin);
      if (method === 'GET') {
        Object.entries(params).forEach(([k,v]) => u.searchParams.set(k, v));
      }
      for (let attempt = 0; attempt <= retries; attempt++) {
        try {
          const res = await fetch(u.toString(), {
            method,
            headers: bearerHeaders(headersExtra),
            body,
            cache: 'no-store',
            credentials: 'omit',
          });
          if ([401].includes(res.status)) throw new Error('Unauthorized');
          if ([429,502,503,504].includes(res.status)) {
            if (attempt === retries) throw new Error('Server temporarily unavailable.');
            const wait = backoffMs * Math.pow(2, attempt) + Math.floor(Math.random()*250);
            await new Promise(r => setTimeout(r, wait));
            continue;
          }
          const text = await res.text();
          if (!text) return {};
          if (/^\s*</.test(text) && !text.trim().startsWith('{')) throw new Error('Unexpected HTML from server');
          return JSON.parse(text);
        } catch (e) {
          if (attempt === retries) throw e;
          const wait = backoffMs * Math.pow(2, attempt) + Math.floor(Math.random()*250);
          await new Promise(r => setTimeout(r, wait));
        }
      }
    }

    const titleCase = (s='') => s.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
    const fmtDate   = (s) => s ? new Date(String(s).replace(' ','T')).toLocaleString() : '—';

    // ---- editor helpers for toolbar ----
    function exec(cmd, value=null){ document.execCommand(cmd, false, value); }
    function applyAlign(val){
      if (val==='left') exec('justifyLeft');
      if (val==='center') exec('justifyCenter');
      if (val==='right') exec('justifyRight');
    }
    function applyFontSize(px){
      if (!px) return;
      const sel = window.getSelection();
      if (!sel.rangeCount) return;
      const range = sel.getRangeAt(0);
      const span = document.createElement('span');
      span.style.fontSize = px;
      span.textContent = sel.toString() || ' ';
      range.deleteContents(); range.insertNode(span);
    }

    // ---- export helpers (DOCX/PDF) ----
    

function stripLeadingNumbersInLis(root){
  root.querySelectorAll('ol li').forEach(li=>{
    li.innerHTML = (li.innerHTML || '').replace(/^\s*(\(?\d+\)?[.)]|\d+)\s+/, '');
  });
}

function cleanLeadingNums(text){
  // strips 1., 1), (1), A., A), (A) at start
  return String(text || '').replace(/^\s*(\(?\d+\)?[.)]|\(?[A-D]\)?[.)])\s*/i, '').trim();
}

function flattenAnswerKeyOLs(root){
  root.querySelectorAll('ol').forEach(ol=>{
    const wrap = document.createElement('div');
    Array.from(ol.querySelectorAll(':scope > li')).forEach((li, i)=>{
      const line = document.createElement('p');
      line.style.margin = '0 0 4px 0';
      // use textContent to ignore nested tags’ numbering, then rebuild
      const text = cleanLeadingNums(li.textContent);
      line.textContent = `${i+1}. ${text}`;
      wrap.appendChild(line);
    });
    ol.replaceWith(wrap);
  });
}


function getAnswerKeyOnlyHTML(){
  const paper = document.getElementById('paper');
  if (!paper) return '';

  const root = paper.cloneNode(true);
  let frag = null;

  // 1) Compiled keys host
  const keyHost = root.querySelector('[data-compiled-keys]');
  if (keyHost){
    frag = keyHost.cloneNode(true);
  } else {
    // 2) Per-set keys
    const perSet = Array.from(root.querySelectorAll('[data-key]'));
    if (perSet.length){
      frag = document.createElement('div');
      perSet.forEach(block => frag.appendChild(block.cloneNode(true)));
    } else {
      // 3) Heading text "Answer Key(s)"
      const ak = Array.from(root.querySelectorAll('h1,h2,h3,h4,div,section,p'))
        .find(el => /answer\s*keys?/i.test((el.textContent||'').trim()));
      if (ak){
        frag = document.createElement('div');
        let n = ak;
        while (n){ frag.appendChild(n.cloneNode(true)); n = n.nextSibling; }
      } else {
        // 4) After last footer
        const footers = Array.from(root.querySelectorAll('footer'));
        const lastFooter = footers.reverse().find(f =>
          /Generated by Exam Maker - Agents/i.test(f.textContent || '') ||
          (f.className || '').includes('border-t')
        );
        if (lastFooter){
          frag = document.createElement('div');
          let n = lastFooter.nextSibling;
          while (n){ frag.appendChild(n.cloneNode(true)); n = n.nextSibling; }
        }
      }
    }
  }

  if (!frag) return '';

  // Normalize styles that break html-to-pdfmake
  frag.querySelectorAll('ol').forEach(ol => ol.removeAttribute('style'));
  frag.querySelectorAll('[style]').forEach(el => {
    el.style.columnCount = '';
    el.style.columns = '';
    el.style.webkitColumns = '';
    el.style.MozColumns = '';
  });

  // Prevent "1. 1. A" duplication
  stripLeadingNumbersInLis(frag);
  
  
  if (!frag) return '';

    flattenAnswerKeyOLs(frag);     // <— converts OL to plain numbered paragraphs
    // (optional) if you still keep some OLs elsewhere, you can also strip styles:
    frag.querySelectorAll('[style]').forEach(el => {
      el.style.columnCount = '';
      el.style.columns = '';
      el.style.webkitColumns = '';
      el.style.MozColumns = '';
    });

  return frag.innerHTML;
}



function downloadPDF_AnswerKeyOnly(title) {
  const docTitle = ((title || 'Answer Keys') + ' - ANSWER KEY').toUpperCase();
  let html = DOMPurify.sanitize(getAnswerKeyOnlyHTML(), { ADD_ATTR: ['style'] });
  if (!html.trim()) { alert('No Answer Key found on the page.'); return; }

  html = stripUnsupportedFonts(html);
  let pdfContent = window.htmlToPdfmake(html, { window });
  if (!Array.isArray(pdfContent)) pdfContent = [pdfContent];
  pdfContent = sanitizePdfNode(pdfContent);

  const docDefinition = {
    info: { title: docTitle },
    pageSize: 'A4',
    pageMargins: [40, 60, 40, 60],
    content: [
      { text: docTitle, style: 'header', alignment: 'center', margin: [0,0,0,12] },
      ...pdfContent
    ],
    styles: { header: { fontSize: 16, bold: true } },
    defaultStyle: { font: 'Roboto', fontSize: 12, lineHeight: 1.4 }
  };

  pdfMake.createPdf(docDefinition).download(docTitle + '.pdf');
}

    
    function getHTMLWithoutAnswerKey() {
  const paper = document.getElementById('paper');
  if (!paper) return '';

  // Work on a clone so we don't touch the live editor
  const root = paper.cloneNode(true);

  // 1) Prefer cutting at an explicit "Answer Key" heading if present
  let cutStart =
    Array.from(root.querySelectorAll('h1,h2,h3,h4,section,div,p'))
      .find(el => /answer\s*key/i.test((el.textContent || '').trim()));

  // 2) Else cut AFTER the last footer that matches your signature text
  if (!cutStart) {
    const footers = Array.from(root.querySelectorAll('footer'));
    const lastFooter = footers.reverse().find(f =>
      /Generated by Exam Maker - Agents/i.test(f.textContent || '')
      || (f.className || '').includes('mt-6')  // your class combo
    );
    if (lastFooter) {
      // remove everything AFTER the footer, keep the footer itself
      cutStart = lastFooter.nextSibling;
    }
  }

  // If we found a cut point, remove everything from there to the end
  if (cutStart) {
    let n = cutStart;
    while (n) {
      const next = n.nextSibling;
      if (n.parentNode) n.parentNode.removeChild(n);
      n = next;
    }
  }

  return root.innerHTML;
}
    
    
    function forceImageWidths(node, max = 480){
  if (Array.isArray(node)) return node.map(n => forceImageWidths(n, max));

  if (node && typeof node === 'object'){
    if (node.image) {
      // prefer numeric width, coerce strings like "480px" to number
      let w = numOr(undefined, node.width);
      if (!Number.isFinite(w) || w <= 0) w = max;
      if (w > max) w = max;
      node.width = w;
      // Remove conflicting sizing
      delete node.fit;
      delete node.height;
    }

    // Recurse into containers
    ['content','stack','columns','ul','ol','table','header','footer','background']
      .forEach(k => { if (k in node) node[k] = forceImageWidths(node[k], max); });
  }
  return node;
}

    
    function getCleanHTMLForPdf(){
  const node = document.getElementById('paper').cloneNode(true);

  // 1) Force IMG width in px (no %, no auto). Clamp to printable width ~480px
  const MAX_W = 480;
  node.querySelectorAll('img').forEach(img => {
    // use natural width if available, otherwise 480
    const nat = Math.max(1, img.naturalWidth || MAX_W);
    const w = Math.min(nat, MAX_W);
    img.removeAttribute('height');
    img.style.height = '';             // let height auto-scale
    img.style.maxWidth = '';           // remove % max-width
    img.style.width = w + 'px';
    img.setAttribute('width', w);      // helps html-to-pdfmake
  });

  // 2) Replace "pt" units with px in style attributes (1pt ≈ 1.333px)
  const PT_TO_PX = 1.3333333;
  const walk = node.querySelectorAll('*');
  walk.forEach(el => {
    if (el.hasAttribute('style')) {
      let s = el.getAttribute('style') || '';
      s = s.replace(/(-?\d*\.?\d+)\s*pt/gi, (_, n) =>
        Math.round(parseFloat(n) * PT_TO_PX) + 'px'
      );
      el.setAttribute('style', s);
    }
  });

  // 3) Strip obviously bad numeric styles (e.g., width: ; margin: auto)
  walk.forEach(el => {
    const st = el.style;
    if (!st) return;
    if (st.width && /%|auto/i.test(st.width)) st.width = '';
    if (st.height && /%|auto/i.test(st.height)) st.height = '';
    if (st.margin && /auto/i.test(st.margin)) st.margin = '';
    if (st.lineHeight && /auto/i.test(st.lineHeight)) st.lineHeight = '';
  });

  // Remove <script>/<style>
  node.querySelectorAll('script,style').forEach(el => el.remove());

  return node.innerHTML;
}


function numOr(defaultVal, v){
  if (typeof v === 'number') return Number.isFinite(v) ? v : defaultVal;
  if (typeof v === 'string'){
    const n = parseFloat(v);
    return Number.isFinite(n) ? n : defaultVal;
  }
  return defaultVal;
}
    
     // ORIGINAL  
  function sanitizePdfNode(node){
  if (Array.isArray(node)) return node.map(sanitizePdfNode);

  if (node && typeof node === 'object'){
    // Fix table.widths like ["18%","64%","18%"]
    if (node.table && Array.isArray(node.table.widths)) {
      node.table.widths = node.table.widths.map(w => {
        if (w === '*' || w === 'auto') return w;
        const n = parseFloat(w);
        return Number.isFinite(n) ? n : '*';
      });
    }

    // columns must be an array
    if ('columns' in node && !Array.isArray(node.columns)) {
      node.columns = [ node.columns ];
    }

    // normalize numeric fields
    ['fontSize','lineHeight','width','height','characterSpacing','leadingIndent','opacity']
      .forEach(k => {
        if (k in node){
          const n = (typeof node[k] === 'string') ? parseFloat(node[k]) : node[k];
          if (Number.isFinite(n)) node[k] = n; else delete node[k];
        }
      });

    if ('margin' in node){
      if (Array.isArray(node.margin)) {
        node.margin = node.margin.map(v => Number.isFinite(parseFloat(v)) ? parseFloat(v) : 0);
      } else {
        const n = parseFloat(node.margin);
        node.margin = Number.isFinite(n) ? n : 0;
      }
    }

    // Recurse into known containers
    ['content','stack','columns','header','footer','background','ul','ol','table','layout']
      .forEach(k=>{ if (k in node) node[k] = sanitizePdfNode(node[k]); });
  }
  return node;
}
    
    async function urlToDataURL(src) {
  try {
    // already data URL
    if (/^data:/i.test(src)) return src;
    const res = await fetch(src, { mode: 'cors' }); // try CORS first
    const blob = await res.blob();
    const reader = new FileReader();
    return await new Promise((resolve, reject) => {
      reader.onload = () => resolve(reader.result);
      reader.onerror = reject;
      reader.readAsDataURL(blob);
    });
  } catch (e) {
    console.warn('Falling back to no-CORS image fetch for', src, e);
    // last resort: try no-cors (may still fail on some hosts)
    const res = await fetch(src, { mode: 'no-cors' }).catch(()=>null);
    if (!res || !res.body) return src; // keep original if we really can’t inline
    const blob = await res.blob();
    const reader = new FileReader();
    return await new Promise((resolve, reject) => {
      reader.onload = () => resolve(reader.result);
      reader.onerror = reject;
      reader.readAsDataURL(blob);
    });
  }
}

// Inline all <img> src into data: URLs and optionally clamp width
async function inlineAndClampImages(html, clampPx = null, clampIn = null) {
  const node = document.createElement('div');
  node.innerHTML = html;

  const imgs = Array.from(node.querySelectorAll('img'));
  for (const img of imgs) {
    // Inline
    const dataUrl = await urlToDataURL(img.getAttribute('src') || '');
    img.setAttribute('src', dataUrl);

    // Clamp
    if (clampPx != null) {
      img.setAttribute('width', String(clampPx));
      img.removeAttribute('height');
      img.style.width = clampPx + 'px';
      img.style.height = 'auto';
      img.style.maxWidth = '';
      img.style.maxHeight = '';
    }
    if (clampIn != null) {
      img.removeAttribute('width');
      img.removeAttribute('height');
      img.style.width = clampIn + 'in';
      img.style.height = 'auto';
      img.style.maxWidth = '';
      img.style.maxHeight = '';
    }
  }
  return node.innerHTML;
}

// Keep your font stripping
function stripUnsupportedFonts(html){
  return html.replace(/font-family\s*:\s*[^;"]+;?/gi, '');
}

// Get clean editor HTML (you already have this)
function getEditorHTML(){
  const node = document.getElementById('paper').cloneNode(true);
  node.querySelectorAll('script,style').forEach(el => el.remove());
  return node.innerHTML;
}




    function getEditorHTML(){
      // clone to strip scripts/styles for export safety
      const node = $('paper').cloneNode(true);
      node.querySelectorAll('script,style').forEach(el => el.remove());
      return node.innerHTML;
    }
    function stripUnsupportedFonts(html){
      return html.replace(/font-family\s*:\s*[^;"]+;?/gi, '');
    }
 
 
 
 
 
 
 
 // A unique token that *survives* htmlToPdfmake and is easy to find later
const SET_HEADER_TOKEN = '[[__SET_HEADER__]]';
// Clamp helper
const clamp = (n, lo, hi) => Math.max(lo, Math.min(hi, Number(n)||0));

// Put a marker as the *first child* of every .paper (the first set will still get replaced,
// we’ll just avoid pagebreak-before for the first).
function annotateSetsInHTML(html) {
  const root = document.createElement('div');
  root.innerHTML = html;
  const papers = root.querySelectorAll('.paper');
  papers.forEach(p => {
    const marker = document.createElement('p');
    marker.textContent = SET_HEADER_TOKEN;
    p.insertBefore(marker, p.firstChild);
  });
  return root.innerHTML;
}

// ===== DOCX pieces =====

// Your DOCX header (logos + school), already good:

/*
function buildHeaderHTML_DOCX(){
  const L = exportHeaderState.left  || {};
  const R = exportHeaderState.right || {};
  const title = (exportHeaderState.title || '').trim();
  const college = (exportHeaderState.college || '').trim();
  const addr  = (exportHeaderState.addr  || '').trim();
  const clamp = (n, lo, hi) => Math.max(lo, Math.min(hi, Number(n)||0));
  const LW = L.src ? clamp(L.w, 30, 240) : 0;
  const RW = R.src ? clamp(R.w, 30, 240) : 0;

  const leftImg  = L.src ? `<img src="${L.src}" width="${LW}" style="width:${LW}px;height:auto;display:block;margin:0 auto;">` : '';
  const rightImg = R.src ? `<img src="${R.src}" width="${RW}" style="width:${RW}px;height:auto;display:block;margin:0 auto;">` : '';

  return `
  <table width="100%" border="0" style="border-collapse:collapse;table-layout:fixed;margin:0 0 10pt 0">
    <tr>
      <td width="${LW ? LW+20 : 0}" align="left"  valign="middle">${leftImg}</td>
      <td align="center" valign="middle">
        ${title   ? `<div style="font-weight:700;font-size:16pt;line-height:1.2">${escapeHTML(title)}</div>` : ''}
        ${college ? `<div style="font-weight:700;font-size:14pt;line-height:1.2">${escapeHTML(college)}</div>` : ''}
        ${addr    ? `<div style="font-size:11pt;color:#444;line-height:1.2">${escapeHTML(addr)}</div>` : ''}
      </td>
      <td width="${RW ? RW+20 : 0}" align="right" valign="middle">${rightImg}</td>
    </tr>
  </table>`;
}
*/

function buildHeaderHTML_DOCX(cfg){
  const clamp = (n, lo, hi) => Math.max(lo, Math.min(hi, Number(n)||0));
  const L = cfg.left  || {}, R = cfg.right || {};
  const LW = L.src ? clamp(L.w, 30, 240) : 0;
  const RW = R.src ? clamp(R.w, 30, 240) : 0;
  const leftImg  = L.src ? `<img src="${L.src}" width="${LW}" style="width:${LW}px;height:auto;display:block;margin:0 auto;">` : '';
  const rightImg = R.src ? `<img src="${R.src}" width="${RW}" style="width:${RW}px;height:auto;display:block;margin:0 auto;">` : '';
  const title   = cfg.title?.trim(); 
  const college = cfg.college?.trim();
  const addr    = cfg.addr?.trim();

  return `
  <table width="100%" border="0" style="border-collapse:collapse;table-layout:fixed;margin:0 0 10pt 0">
    <tr>
      <td width="${LW ? LW+20 : 0}" align="left"  valign="middle">${leftImg}</td>
      <td align="center" valign="middle">
        ${title   ? `<div style="font-weight:700;font-size:16pt;line-height:1.2">${escapeHTML(title)}</div>` : ''}
        ${college ? `<div style="font-weight:700;font-size:14pt;line-height:1.2">${escapeHTML(college)}</div>` : ''}
        ${addr    ? `<div style="font-size:11pt;color:#444;line-height:1.2">${escapeHTML(addr)}</div>`      : ''}
      </td>
      <td width="${RW ? RW+20 : 0}" align="right" valign="middle">${rightImg}</td>
    </tr>
  </table>`;
}

function buildPdfHeaderNode(cfg) {
  const clamp = (n, lo, hi) => Math.max(lo, Math.min(hi, Number(n)||0));
  const L = cfg.left  || {}, R = cfg.right || {};
  const leftW  = L.src ? clamp(L.w, 30, 240) : 0;
  const rightW = R.src ? clamp(R.w, 30, 240) : 0;

  const middle = [];
  if (cfg.title)   middle.push({ text: cfg.title,   bold:true, fontSize:16, alignment:'center', margin:[0,0,0,2] });
  if (cfg.college) middle.push({ text: cfg.college, bold:true, fontSize:12, alignment:'center', margin:[0,0,0,2] });
  if (cfg.addr)    middle.push({ text: cfg.addr,    fontSize:11, color:'#444', alignment:'center' });

  return {
    columns: [
      { width: leftW  || 0, alignment:'left',  stack: L.src ? [{ image: L.src, width: leftW  }] : [] },
      { width: '*',   alignment:'center', stack: middle },
      { width: rightW || 0, alignment:'right', stack: R.src ? [{ image: R.src, width: rightW }] : [] },
    ],
    columnGap: 10,
    margin: [0,0,0,2]
  };
}


// Plain-text name lines for DOCX (no borders/tables; width blocks for alignment)
function makePlainTextHeader_HTML() {
  return `
  <div style="width:680px; font-size:11pt; line-height:1.2; margin:0 0 8pt 0">
    <div>
      <span style="display:inline-block; width:440px;">Name: ____________________________________</span>
      <span style="display:inline-block; width:20px;"></span>
      <span style="display:inline-block; width:220px;">Date: ____________________</span>
    </div>
    <div>
      <span style="display:inline-block; width:440px;">Year &amp; Section: _________________________</span>
      <span style="display:inline-block; width:20px;"></span>
      <span style="display:inline-block; width:220px;">Score: ____________________</span>
    </div>
  </div>`;
}
 
 // Inject the DOCX header + name-lines HTML at the top of every .paper
 /*
async function injectHeaderPerSet_DOCX(html) {
  const root = document.createElement('div');
  root.innerHTML = html;

  let hdr = buildHeaderHTML_DOCX();
  hdr = await inlineAndClampImages(hdr, null, null); // ensure logos embed

  const nameLines = makePlainTextHeader_HTML();

  root.querySelectorAll('.paper').forEach((paper, i) => {
    // optional page-break before every set except first (Word-friendly)
    if (i > 0) {
      const br = document.createElement('p');
      br.setAttribute('style','page-break-before:always;margin:0;padding:0;');
      br.innerHTML = '&nbsp;';
      paper.parentNode.insertBefore(br, paper);
    }
    const wrap = document.createElement('div');
    wrap.innerHTML = hdr + nameLines;
    // insert at top of the paper
    paper.insertBefore(wrap, paper.firstChild);
  });

  return root.innerHTML;
}
*/
async function injectHeaderPerSet_DOCX(html, cfg) {
  const root = document.createElement('div');
  root.innerHTML = html;

  let hdr = buildHeaderHTML_DOCX(cfg);
  hdr = await inlineAndClampImages(hdr, null, null);

  const nameLines = makePlainTextHeader_HTML();
  root.querySelectorAll('.paper').forEach((paper, i) => {
    if (i > 0) {
      const br = document.createElement('p');
      br.setAttribute('style','page-break-before:always;margin:0;padding:0;');
      br.innerHTML = '&nbsp;';
      paper.parentNode.insertBefore(br, paper);
    }
    const wrap = document.createElement('div');
    wrap.innerHTML = hdr + nameLines;
    paper.insertBefore(wrap, paper.firstChild);
  });

  return root.innerHTML;
}

// ===== PDF pieces =====
/*
//orig
function buildPdfHeaderNode() {
  const L = exportHeaderState.left  || {};
  const R = exportHeaderState.right || {};
  const title   = (exportHeaderState.title   || '').trim();
  const college = (exportHeaderState.college || '').trim();
  const addr    = (exportHeaderState.addr    || '').trim();

  const clamp = (n, lo, hi) => Math.max(lo, Math.min(hi, Number(n)||0));
  const leftW  = L.src ? clamp(L.w, 30, 240) : 0;
  const rightW = R.src ? clamp(R.w, 30, 240) : 0;

  const leftImg  = L.src ? { image: L.src, width: leftW }  : null;
  const rightImg = R.src ? { image: R.src, width: rightW } : null;

  const middle = [];
  if (title)   middle.push({ text: title,   bold:true, fontSize:16, alignment:'center', margin:[0,0,0,2] });
  if (college) middle.push({ text: college, bold:true, fontSize:12, alignment:'center', margin:[0,0,0,2] });
  if (addr)    middle.push({ text: addr,    fontSize:11, color:'#444', alignment:'center' });

  return {
    columns: [
      { width: leftW  || 0, alignment:'left',  stack: leftImg  ? [leftImg]  : [] },
      { width: '*',   alignment:'center',      stack: middle },
      { width: rightW || 0, alignment:'right', stack: rightImg ? [rightImg] : [] },
    ],
    columnGap: 10,
    margin: [0,0,0,8]
  };
}
*/

function buildNameDateRow() {
  const l1Left  = { text: 'Name: _______________________________________', margin:[0,0,0,2] };
  const l1Right = { text: 'Date: ____________________', alignment: 'right', margin:[0,0,0,2] };

  const l2Left  = { text: 'Year & Section: _____________________________', margin:[0,0,0,0] };
  const l2Right = { text: 'Score: ____________________', alignment: 'right', margin:[0,0,0,0] };

  return {
    margin: [0, 0, 0, 4],
    columnGap: 10,
    stack: [
      { columns: [ { width:'*', ...l1Left },  { width: 200, ...l1Right } ] },
      { columns: [ { width:'*', ...l2Left },  { width: 200, ...l2Right } ] }
    ]
  };
}


/* small helper: deep-clone any node before reusing it */
const cloneNode = (obj) => JSON.parse(JSON.stringify(obj));

/* one bundle to drop before each set */
function buildHeaderBlock() {
  const hdr = buildPdfHeaderNode();
  const row = buildNameDateRow();
  return [hdr, row];
}



// pdfmake name lines (columns so right side truly right-aligned)
function buildPdfStudentLinesNode() {
  const l1Left  = { text: 'Name: ____________________________________',   margin:[0,0,8,2] };
  const l1Right = { text: 'Date: ____________________', alignment: 'right', margin:[0,0,0,2] };

  const l2Left  = { text: 'Year & Section: _________________________' };
  const l2Right = { text: 'Score: ____________________', alignment: 'right' };

  return {
    margin:[0,0,0,8],
    columnGap: 10,
    stack: [
      { columns: [ { width:'*', ...l1Left },  { width:220, ...l1Right } ] },
      { columns: [ { width:'*', ...l2Left },  { width:220, ...l2Right } ] }
    ]
  };
}

// Replace each SET marker token with (header + student lines) and add pageBreak before all but first
function injectHeaderPerSet_PDF(nodes) {
  let seen = 0;
  const headerNode = buildPdfHeaderNode();
  const nameNode   = buildPdfStudentLinesNode();

  const replace = arr => {
    const out = [];
    for (const n of arr) {
      // pdfmake simple text node
      if (n && typeof n === 'object' && typeof n.text === 'string' && n.text.includes(SET_HEADER_TOKEN)) {
        // page break BEFORE the header for sets after the first
        if (seen > 0) out.push({ text:'', pageBreak:'before' });
        out.push(headerNode);
        out.push(nameNode);
        seen++;
        continue; // skip the token itself
      }

      // recurse into containers
      if (n && typeof n === 'object') {
        ['stack','content','columns','ul','ol'].forEach(k=>{
          if (Array.isArray(n[k])) n[k] = replace(n[k]);
        });
        if (n.table && Array.isArray(n.table.body)) {
          n.table.body = n.table.body.map(row => Array.isArray(row) ? replace(row) : row);
        }
      }
      out.push(n);
    }
    return out;
  };

  return Array.isArray(nodes) ? replace(nodes) : nodes;
}
 
 function tightenPdfSpacing(node){
  const walk = (n)=>{
    if (Array.isArray(n)) return n.map(walk);
    if (!n || typeof n !== 'object') return n;


if ('text' in n && !('table' in n) && !('image' in n) && !('columns' in n)) {
  if (!n.margin) n.margin = [0, 1, 0, 3]; // was [0,2,0,4]
}
if ('ul' in n || 'ol' in n) { if (!n.margin) n.margin = [0, 1, 0, 3]; }
if ('table' in n){ if (!n.margin) n.margin = [0, 2, 0, 4]; }


    // Lists / stacks / tables
    if ('stack' in n || 'content' in n || 'columns' in n) {
      ['stack','content','columns','ul','ol'].forEach(k=>{ if (n[k]) n[k] = walk(n[k]); });
    }
    
    return n;
  };
  return walk(node);
}


// Read any of: data-w, width="", style="width:123px", or naturalWidth.
// Then force pixel width + auto height so htmlToPdfmake emits clean numbers.
function fixFiguresForPdf(html, maxPx = 480) {
  const root = document.createElement('div');
  root.innerHTML = html;

  const px = v => {
    const n = parseFloat(v);
    return Number.isFinite(n) ? Math.max(1, Math.min(n, maxPx)) : NaN;
  };

  // <figure> containers (optional)
  root.querySelectorAll('figure').forEach(fig => {
    let w = px(fig.getAttribute('data-w'))
         || px(fig.getAttribute('width'))
         || (fig.style?.width?.endsWith('px') ? px(fig.style.width) : NaN);
    if (!Number.isFinite(w)) w = maxPx;
    fig.style.width = w + 'px';
  });

  // <img> elements
  root.querySelectorAll('img').forEach(img => {
    let w = px(img.getAttribute('data-w'))
         || px(img.getAttribute('width'))
         || (img.style?.width?.endsWith('px') ? px(img.style.width) : NaN)
         || px(img.naturalWidth);
    if (!Number.isFinite(w)) w = maxPx;
    w = Math.min(w, maxPx);
    img.setAttribute('width', String(w));
    img.removeAttribute('height');
    img.style.width = w + 'px';
    img.style.height = 'auto';
    img.style.maxWidth = '';
    img.style.maxHeight = '';
  });

  return root.innerHTML;
}


// Pick a sensible default max printable width
const FIGURE_MAX_PX = 480; // ~A4 inner width with 40pt side margins

function fixFigureSizesHTML(html, maxPx = FIGURE_MAX_PX){
  const node = document.createElement('div');
  node.innerHTML = html;

  node.querySelectorAll('img').forEach(img=>{
    // Allow per-image override via data-w="320" (or width attr), else clamp to maxPx
    const want = parseInt(img.getAttribute('data-w') || img.getAttribute('width') || 0, 10);
    const w = Math.max(30, Math.min(maxPx, Number.isFinite(want) && want>0 ? want : maxPx));

    img.setAttribute('width', String(w));     // attribute for Word
    img.removeAttribute('height');
    img.style.width = w + 'px';               // inline style for browsers
    img.style.height = 'auto';
    img.style.maxWidth = '';                  // kill % sizing
    img.style.maxHeight = '';
  });

  return node.innerHTML;
}

 /* orig
 async function downloadDOCX_withHeader(title){
  const fname = (title || 'exam_paper').trim();

  let bodyHtml = DOMPurify.sanitize(getHTMLWithoutAnswerKey(), { ADD_ATTR: ['style'] });
  bodyHtml = fixFigureSizesHTML(bodyHtml, 280);                 // your figure size clamp
  bodyHtml = await injectHeaderPerSet_DOCX(bodyHtml);           // <— inject per-set header + name lines

  const html = `
<!DOCTYPE html><html><head><meta charset="utf-8">
<style>body{font-family:Arial,sans-serif;font-size:14pt;line-height:1.6}</style>
</head><body>
  ${bodyHtml}
</body></html>`;

  const blob = window.htmlDocx.asBlob(html);
  saveAs(blob, fname.replace(/[^\w\-\. ]+/g,'_') + ".docx");
}
*/

async function downloadDOCX_withHeader(title){
  const cfg = getCurrentHeaderCfg();              // ← read current UI
  let bodyHtml = DOMPurify.sanitize(getHTMLWithoutAnswerKey(), { ADD_ATTR: ['style'] });
  bodyHtml = fixFigureSizesHTML(bodyHtml, 280);
  bodyHtml = await injectHeaderPerSet_DOCX(bodyHtml, cfg);
  const blob = window.htmlDocx.asBlob(`<!doctype html><html><head><meta charset="utf-8"><style>body{font-family:Arial,sans-serif;font-size:14pt;line-height:1.6}</style></head><body>${bodyHtml}</body></html>`);
  saveAs(blob, (title||'exam_paper').replace(/[^\w\-\. ]+/g,'_') + ".docx");
}
/* orig
async function downloadPDF_withHeader(title){
  const docTitle = (title || 'Exam Paper').toUpperCase();

  // Clean the full HTML first (images fixed, page breaks between .paper)
  let html = DOMPurify.sanitize(getHTMLWithoutAnswerKey(), { ADD_ATTR: ['style'] });
  html = stripUnsupportedFonts(html);
  html = fixFiguresForPdf(html, 480);

  // Grab each .paper (one set per .paper)
  const host = document.createElement('div');
  host.innerHTML = html;
  const sets = Array.from(host.querySelectorAll('.paper'));

  let content = [];
  const headerBlock = buildHeaderBlock();            // built once…
  for (let i=0; i<sets.length; i++) {
    const setHtml = sets[i].outerHTML;

    // convert this set only
    let nodes = window.htmlToPdfmake(setHtml, { window });
    if (!Array.isArray(nodes)) nodes = [nodes];
    nodes = sanitizePdfNode(nodes);
    nodes = tightenPdfSpacing(nodes);

    if (i > 0) content.push({ text:'', pageBreak:'before' });  // new page for every next set

    // IMPORTANT: deep-clone header elements so pdfmake doesn’t mutate the originals
    content.push(...cloneNode(headerBlock));
    content.push(...nodes);
  }

  // Fallback: if no .paper found, keep old behavior (single document)
  if (sets.length === 0) {
    let bodyNodes = window.htmlToPdfmake(html, { window });
    if (!Array.isArray(bodyNodes)) bodyNodes = [bodyNodes];
    bodyNodes = sanitizePdfNode(bodyNodes);
    bodyNodes = tightenPdfSpacing(bodyNodes);
    content.push(...buildHeaderBlock());
    content.push(...bodyNodes);
  }

  content = sanitizePdfNode(content);

  pdfMake.createPdf({
    info: { title: docTitle },
    pageSize: 'A4',
    pageMargins: [40, 54, 40, 54],            // a bit tighter
    content,
    defaultStyle: { font:'Roboto', fontSize:12, lineHeight:1.15 }
  }).download(docTitle + '.pdf');
}
*/

function buildHeaderBlock(cfg){ 
  return [ buildPdfHeaderNode(cfg), buildNameDateRow() ]; 
}



async function downloadPDF_withHeader(title){
  const cfg = getCurrentHeaderCfg();              // ← read current UI
  let html = DOMPurify.sanitize(getHTMLWithoutAnswerKey(), { ADD_ATTR: ['style'] });
  html = stripUnsupportedFonts(html);
  html = fixFiguresForPdf(html, 380);

  const host = document.createElement('div'); host.innerHTML = html;
  const sets = Array.from(host.querySelectorAll('.paper'));

  let content = [];
  for (let i=0; i<sets.length; i++){
    let nodes = window.htmlToPdfmake(sets[i].outerHTML, { window });
    if (!Array.isArray(nodes)) nodes = [nodes];
    nodes = sanitizePdfNode(nodes);
    nodes = tightenPdfSpacing(nodes);
    if (i>0) content.push({ text:'', pageBreak:'before' });
    content.push(...JSON.parse(JSON.stringify(buildHeaderBlock(cfg)))); // deep clone
    content.push(...nodes);
  }
  if (!sets.length){
    let nodes = window.htmlToPdfmake(html, { window });
    if (!Array.isArray(nodes)) nodes = [nodes];
    nodes = sanitizePdfNode(nodes);
    nodes = tightenPdfSpacing(nodes);
    content.push(...buildHeaderBlock(cfg), ...nodes);
  }

  pdfMake.createPdf({
    info:{ title:(title||'Exam Paper').toUpperCase() },
    pageSize:'A4',
    pageMargins:[40,54,40,54],
    content,
    defaultStyle:{ font:'Roboto', fontSize:12, lineHeight:1.15 }
  }).download((title||'Exam Paper') + '.pdf');
}


// Optional: catch unhandled promise rejections from pdfmake internals
window.addEventListener('unhandledrejection', (e) => {
  console.error('Unhandled rejection:', e.reason);
  alert('PDF export failed during layout. If the exam has images or % widths, set fixed pixel widths or export as DOCX.');
});



    // ---- state + load ----
    const examId = (() => {
      const m = location.pathname.match(/\/exam\/(\d+)(?:\/)?$/i);
      if (m && m[1]) return m[1];
      const qs = new URLSearchParams(location.search);
      return qs.get('id');
    })();
    if (!examId) { location.replace('/my-exams'); }

    (function fillSidebarQuick(){
      // quick paint name + avatar from cache; optional refresh omitted for brevity
      const cache = (()=>{ try{return JSON.parse(localStorage.getItem(CACHE_KEY)||'{}')}catch{return{}}})();
      $('displayName') && ($('displayName').textContent = cache.name || cache.username || cache.email || 'User');
      const img = $('profilePic');
      if (img) img.src = cache.profile_picture || '/images/default-avatar.png';
    })();

    let loadedExam = { id:null, title:'', description:'', exam_type:'', number_of_questions:0, sets_of_exam:0 };

    async function loadExam(){
      try {
        const r = await getJSONWithRetry(api.examShow, { params: { id: examId } });
        if (r?.status !== 'success' || !r.exam) throw new Error(r?.message || 'Exam not found');

        const e = r.exam;
        loadedExam = {
          id: e.id || examId,
          title: e.title || 'Untitled Exam',
          description: e.description || '',
          exam_type: e.exam_type || '',
          number_of_questions: +e.number_of_questions || 0,
          sets_of_exam: +e.sets_of_exam || 0
        };

        $('examTitle').textContent     = loadedExam.title;
        $('examDesc').textContent      = loadedExam.description || '—';
        $('examStatus').textContent    = titleCase(e.status || 'generated');
        $('examType').textContent      = titleCase(loadedExam.exam_type);
        $('examQuestions').textContent = loadedExam.number_of_questions;
        $('examSets').textContent      = loadedExam.sets_of_exam;
        $('createdAt').textContent     = fmtDate(e.created_at);
        $('updatedAt').textContent     = fmtDate(e.updated_at);

        const detected  = +e.computed_questions || 0;
        const requested = loadedExam.number_of_questions;
        if (detected && requested && detected !== requested) {
          $('examDetected') && ($('examDetected').textContent = `Detected from paper: ${detected}`);
        }

        $('paper').innerHTML = e.body_html
          ? e.body_html
          : '<div class="bg-yellow-50 border border-yellow-200 text-yellow-700 rounded p-4">No saved content for this exam.</div>';

        $('saveNote').textContent = 'Loaded';
      } catch (err) {
        console.error('exam_show failed:', err);
        $('paper').innerHTML = '<div class="bg-red-50 border border-red-200 text-red-700 rounded p-4">Failed to load exam.</div>';
        $('examTitle').textContent = 'Exam Not Found';
        $('examDesc').textContent  = 'We couldn’t load this exam.';
      }
    }
    loadExam();

    // ---- toolbar wiring ----
    document.querySelectorAll('[data-cmd]').forEach(btn=>{
      btn.addEventListener('click', () => exec(btn.dataset.cmd));
    });
    document.querySelectorAll('[data-align]').forEach(btn=>{
      btn.addEventListener('click', () => applyAlign(btn.dataset.align));
    });
    $('fontSizeSel').addEventListener('change', e => applyFontSize(e.target.value));

    // dirty flag
    $('paper').addEventListener('input', ()=> $('saveNote').textContent = 'Unsaved changes…');
    $('examTitle').addEventListener('input', ()=> $('saveNote').textContent = 'Unsaved changes…');
    $('examDesc').addEventListener('input', ()=> $('saveNote').textContent = 'Unsaved changes…');

    // ---- Save changes -> api/exam_update.php ----
    async function saveEditedExam(){
      const title = $('examTitle').innerText.trim();
      const description = $('examDesc').innerText.trim();
      // sanitize with styles allowed so we keep inline formatting
      const cleanHtml = DOMPurify.sanitize($('paper').innerHTML, { ADD_ATTR: ['style'] });

      const fd = new FormData();
        fd.append('id', loadedExam.id);
        if (title) fd.append('title', title);
        fd.append('description', description);
        fd.append('body_html', cleanHtml);
        
        // send token in the body as a fallback for PHP environments that strip Authorization
        fd.append('token', RAW_JWT);
        
        const res = await fetch('/api/exam_update.php', {
          method: 'POST',
          // keep Authorization header AND body token for maximum compatibility
          headers: bearerHeaders(),
          body: fd
        });

      const data = await res.json().catch(()=>({status:'error',message:'Invalid server response'}));
      if (data.status !== 'success') throw new Error(data.message || 'Save failed');
      $('updatedAt').textContent = fmtDate(data.updated_at || new Date().toISOString());
      $('saveNote').textContent = 'Saved';
      loadedExam.title = title || loadedExam.title;
    }

    $('btnSave').addEventListener('click', async ()=>{
      const btn = $('btnSave');
      btn.disabled = true; btn.classList.add('opacity-60','pointer-events-none');
      try {
        await saveEditedExam();
        alert('Exam saved successfully.');
      } catch(e){
        console.error(e);
        alert(e.message || 'Save failed.');
      } finally {
        btn.disabled = false; btn.classList.remove('opacity-60','pointer-events-none');
      }
    });

     // ---- Client-side exports from current editor content ----
    $('btnDocx').addEventListener('click', ()=>{
      showExportHeaderModal('docx');
    });
    

    // ---- logout ----
    $('logoutBtn')?.addEventListener('click', () => {
      localStorage.removeItem(TOKEN_KEY);
      localStorage.removeItem(CACHE_KEY);
      location.replace('/login');
    });
    
    
    const exportHeaderState = { left:{src:'',w:80}, right:{src:'',w:80}, title:'', college: '', addr:'' };

function showExportHeaderModal(kind){ // kind: 'docx' | 'pdf'
  const el = $('exportHeaderModal');
  // el.dataset.kind = kind;
  el.classList.remove('hidden');
}

function hideExportHeaderModal(){
  $('exportHeaderModal').classList.add('hidden');
}

function readFileToDataURL(file){
  return new Promise((res,rej)=>{
    const r=new FileReader();
    r.onload=()=>res(String(r.result||'')); r.onerror=rej; r.readAsDataURL(file);
  });
}

function wireExportHeaderModal(){
  const g = id => document.getElementById(id);
  const bind = (id, handler) => { const el = g(id); if (el) el.onclick = handler; };

  const L  = g('eh_leftFile'),  R  = g('eh_rightFile');
  const LW = g('eh_leftW'),     RW = g('eh_rightW');
  const T  = g('eh_title'), C = g('eh_college'), A  = g('eh_addr');

  if (L)  L.onchange  = async()=>{ if (L.files?.[0]) exportHeaderState.left.src  = await readFileToDataURL(L.files[0]); };
  if (R)  R.onchange  = async()=>{ if (R.files?.[0]) exportHeaderState.right.src = await readFileToDataURL(R.files[0]); };
  if (LW) LW.oninput  = ()=> exportHeaderState.left.w  = Math.max(30, Math.min(240, parseInt(LW.value||'80',10)||80));
  if (RW) RW.oninput  = ()=> exportHeaderState.right.w = Math.max(30, Math.min(240, parseInt(RW.value||'80',10)||80));
  if (T)  T.oninput   = ()=> exportHeaderState.title = T.value.trim();
  if (C)  C.oninput   = ()=> exportHeaderState.college = C.value.trim();
  if (A)  A.oninput   = ()=> exportHeaderState.addr  = A.value.trim();

  bind('eh_cancel', hideExportHeaderModal);
  bind('eh_ok_docx', async ()=>{
    hideExportHeaderModal();
    const t = (document.getElementById('examTitle')?.innerText || 'Exam Paper').trim();
    downloadDOCX_withHeader(t, buildHeaderHTML());
  });
  bind('eh_ok_pdf', async ()=>{
    hideExportHeaderModal();
    const t = (document.getElementById('examTitle')?.innerText || 'Exam Paper').trim();
    downloadPDF_withHeader(t, buildHeaderHTML());
  });
}
document.addEventListener('DOMContentLoaded', wireExportHeaderModal);

// also guard this (it can be null)
document.getElementById('btnPdfKeys')?.addEventListener('click', ()=>{
  downloadPDF_AnswerKeyOnly((document.getElementById('examTitle')?.innerText || 'Exam Paper').trim());
});


function escapeHTML(v){
  if (v == null) return '';
  return String(v)
    .replace(/&/g,'&amp;').replace(/</g,'&lt;')
    .replace(/>/g,'&gt;').replace(/"/g,'&quot;')
    .replace(/'/g,'&#39;');
}

function buildHeaderHTML(){
  const L = exportHeaderState.left, R = exportHeaderState.right;
  const showL = !!L.src, showR = !!R.src;
  const title = exportHeaderState.title || '';
  const addr  = exportHeaderState.addr  || '';

  if (!showL && !showR && !title && !addr) return '';

  const left  = showL ? `<img src="${L.src}" style="display:block;width:${L.w||80}px;height:auto;margin:auto">` : '';
  const right = showR ? `<img src="${R.src}" style="display:block;width:${R.w||80}px;height:auto;margin:auto">` : '';
  const mid = `
    ${title ? `<div style="font-weight:700;font-size:18px;line-height:1.2">${escapeHTML(title)}</div>` : ''}
    ${addr  ? `<div style="font-size:12px;color:#444;line-height:1.2">${escapeHTML(addr)}</div>` : ''}
  `;

  // NOTE: no width="18%" etc; no px strings except inside inline CSS of IMG
  return `
    <table style="border-collapse:collapse;margin-bottom:10px">
      <tr>
        <td style="text-align:left;vertical-align:middle">${left}</td>
        <td style="text-align:center;vertical-align:middle">${mid}</td>
        <td style="text-align:right;vertical-align:middle">${right}</td>
      </tr>
    </table>
  `;
}
 
    
  </script>
</body>
</html>
