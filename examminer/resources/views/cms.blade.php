<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Exam Maker — CMS</title>

  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

  <style>
    :root{
      --bg1:#1e3a8a; --bg2:#3b82f6; --bg3:#60a5fa; --bg4:#93c5fd; --bg5:#1e40af; --bg6:#1d4ed8;
      --text:#0f172a; --muted:#64748b; --border:#e5e7eb; --card:#ffffff; --accent:#3b82f6; --accent2:#2563eb; --ok:#16a34a; --err:#dc2626; --radius:14px;
    }
    @keyframes gradientShift{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}
    html,body{height:100%} *{box-sizing:border-box;margin:0}
    body{font-family:'Inter',system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;color:var(--text);
      background:linear-gradient(-45deg,var(--bg1),var(--bg2),var(--bg3),var(--bg4),var(--bg5),var(--bg6));background-size:400% 400%;animation:gradientShift 15s ease infinite}
    .gradient-animated{position:fixed;inset:0;z-index:0}
    .card{background:#fff;border:1px solid var(--border);border-radius:var(--radius);box-shadow:0 10px 30px rgba(2,8,23,.08);padding:20px}
    .btn{appearance:none;border:0;border-radius:12px;padding:10px 14px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff}
    .btn.secondary{background:#f8fafc;color:#0f172a;border:1px solid var(--border)}
    .btn.warn{background:linear-gradient(135deg,#f97316,#ef4444);color:#fff}
    .muted{color:var(--muted)}
    .teams-card{border:1px solid var(--border);border-radius:16px;background:#fff;box-shadow:0 6px 18px rgba(2,8,23,.06);padding:16px;display:flex;flex-direction:column;gap:10px;transition:.15s}
    .teams-card:hover{transform:translateY(-2px);box-shadow:0 10px 24px rgba(2,8,23,.10)}
    .teams-logo{width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;letter-spacing:.5px}
    .teams-actions i{opacity:.75}
    .pill{font-size:12px;border:1px solid var(--border);border-radius:999px;padding:4px 8px;background:#f8fafc}
    table.cms{width:100%;border-collapse:separate;border-spacing:0;border:1px solid var(--border);border-radius:12px;background:#fff}
    .cms th,.cms td{padding:10px 12px;border-bottom:1px solid var(--border);font-size:14px}
    .cms th{background:#f8fafc;text-align:left;font-weight:700}
    .cms tr:last-child td{border-bottom:none}
    .cms td[contenteditable="true"]:focus{outline:none;box-shadow: inset 0 0 0 2px rgba(59,130,246,.15)}
    @media print{aside,.no-print{display:none!important}; main{padding:0!important}; body{background:#fff!important}}
      .toast{position:fixed;top:18px;right:18px;z-index:9999;display:flex;flex-direction:column;gap:8px}
      .toast .t{background:#111;color:#fff;border-radius:12px;padding:10px 14px;box-shadow:0 10px 24px rgba(0,0,0,.2);opacity:.98}
      .toast .ok{background:#16a34a} .toast .err{background:#dc2626}
</style>

</head>
<body class="min-h-screen">
    <div id="toast" class="toast"></div>

  <div class="absolute inset-0 gradient-animated"></div>

  <script>
    const TOKEN_KEY='jwt_token', CACHE_KEY='profile_cache';
    const jwt = (localStorage.getItem(TOKEN_KEY)||'').replace(/^Bearer\s+/i,'').replace(/^"|"$/g,'');
    if(!jwt) location.replace('/login');
    function parseJwt(t){try{const[,p]=t.split('.');if(!p)return{};return JSON.parse(decodeURIComponent(atob(p.replace(/-/g,'+').replace(/_/g,'/')).split('').map(c=>'%'+('00'+c.charCodeAt(0).toString(16)).slice(-2)).join('')))}catch{return{}}}
    const payload=parseJwt(jwt); if(payload.exp && Date.now()>=payload.exp*1000){localStorage.removeItem(TOKEN_KEY);localStorage.removeItem(CACHE_KEY);location.replace('/login')}
    const $=sel=>document.querySelector(sel); const $$=(sel,r=document)=>Array.from(r.querySelectorAll(sel));
  </script>

  <div class="flex relative z-10">
    <!-- Sidebar (same) -->
    <aside class="w-64 bg-white shadow-2xl min-h-screen border-r border-gray-200 relative">
      <a href="/dashboard" class="flex items-center p-6 border-b border-gray-100 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 transition-all duration-200">
        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mr-3 shadow-lg">
          <img style="width:30px" src="/images/icon.png" alt="logo">
        </div>
        <h1 class="text-xl font-bold text-white">Exam Maker</h1>
      </a>
      <nav class="mt-6 px-4">
        <a href="/dashboard" class="flex items-center px-4 py-3 text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition mb-2"><i class="fas fa-tachometer-alt mr-3"></i> Dashboard</a>
        <a href="/generate-exam" class="flex items-center px-4 py-3 text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition mb-2"><i class="fas fa-wand-magic-sparkles mr-3"></i> Generate Exam</a>
        <a href="/my-exams" class="flex items-center px-4 py-3 text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition mb-2"><i class="fas fa-file-alt mr-3"></i> My Exams</a>
        <a href="/cms" class="flex items-center px-4 py-3 text-white bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg mb-2"><i class="fas fa-clipboard-list mr-3"></i> CMS</a>
      </nav>
      <div class="absolute bottom-0 w-64 p-6 border-t border-gray-100 bg-gray-50">
        <div class="flex items-center mb-4">
          <div class="w-10 h-10 rounded-full mr-3 shadow-md border border-gray-300 overflow-hidden">
            <img id="profilePic" src="/images/default-avatar.png" class="w-full h-full object-cover">
          </div>
          <div>
            <p id="displayName" class="font-bold text-gray-900">User</p>
            <a href="/profile" class="text-sm text-blue-600">View Profile</a>
          </div>
        </div>
        <button id="logoutBtn" class="w-full bg-white text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-100 border border-gray-200 shadow-sm">Logout</button>
      </div>
    </aside>

    <!-- Main -->
    <main class="flex-1 p-8">
      <!-- Toolbar -->
      <div class="flex items-center justify-between mb-5 no-print">
        <div>
          <h2 class="text-2xl font-bold text-white drop-shadow">Classes</h2>
          <p class="text-sm text-white opacity-90">Create and manage your class.</p>
        </div>
        <div class="flex gap-2">
          <button id="btnNewClass" class="btn"><i class="fa fa-plus mr-2"></i>Create Class</button>
          <button id="btnBackToGrid" class="btn secondary hidden"><i class="fa fa-grid-2 mr-2"></i>All Classes</button>
        </div>
      </div>

      <!-- GRID VIEW -->
      <section id="gridView" class="grid md:grid-cols-3 sm:grid-cols-2 gap-5"></section>

      <!-- CLASS VIEW -->
      <section id="classView" class="hidden">
        <div class="card mb-4">
          <div class="flex items-center gap-3">
            <img id="clsLogo" src="/images/icon.png" class="w-12 h-12 rounded-xl border"/>
            <div class="flex-1">
              <div class="flex items-center gap-3">
                <input id="clsName" class="border rounded-lg px-3 py-2 w-72" placeholder="Class name (e.g., IT 305 - 3H)"/>
                <input id="clsSection" class="border rounded-lg px-3 py-2 w-40" placeholder="Section (e.g., 3H-G1)"/>
                <span class="pill" id="clsInfo"></span>
              </div>
              <div class="text-sm mt-2 flex gap-2">
                <input id="clsTeacher" class="border rounded-lg px-3 py-1" placeholder="Teacher name">
                <input id="clsExamTitle" class="border rounded-lg px-3 py-1" placeholder="Default exam title">
                <input id="clsHeader" class="border rounded-lg px-3 py-1 flex-1" placeholder="Header / School / Department">
              </div>
            </div>
            <div class="no-print">
              <label class="btn secondary cursor-pointer">
                <input id="clsLogoInput" type="file" accept="image/*" class="hidden">Change Logo
              </label>
              <button id="btnSaveClassMeta" class="btn ml-2">Save Class</button>
              <button id="btnDeleteClass" class="btn warn ml-2"><i class="fa fa-trash mr-1"></i>Delete</button>

            </div>
          </div>
        </div>

        <div class="card">
          <div class="flex items-center justify-between mb-3">
            <div class="font-bold">Students</div>
            <div class="no-print flex gap-2">
              <button id="btnAddStudent" class="btn">Add Student</button>
              <button id="btnSaveStudents" class="btn secondary">Save Students</button>
              <button id="btnPrint" class="btn secondary">Print</button>
            </div>
          </div>

          <table class="cms">
            <thead>
              <tr>
                <th style="width:34px"></th>
                <th>Student Name</th>
                <th>Section</th>
                <th>Grade</th>
                <th>Score</th>
                <th>Date</th>
                <th>Page #</th>
                <th>Set #</th>
                <th>Exam Title</th>
              </tr>
            </thead>
            <tbody id="stuBody"></tbody>
          </table>
          <div id="msg" class="mt-2 text-sm"></div>
        </div>
      </section>
    </main>
  </div>

  <script>
      /* ================ Helper Toast ============== */
      const toastEl = document.getElementById('toast');
    function toast(msg, type='ok', ms=2200){
      const d=document.createElement('div'); d.className='t '+type; d.textContent=msg;
      toastEl.appendChild(d);
      setTimeout(()=>{ d.style.opacity='0'; d.style.transform='translateY(-6px)'; }, ms-300);
      setTimeout(()=>d.remove(), ms);
    }

      
    // Sidebar profile
    document.addEventListener('DOMContentLoaded',()=>{
      const u=(parseJwt(localStorage.getItem(TOKEN_KEY)||'')?.user)||payload.user||payload.data||payload;
      const cache=JSON.parse(localStorage.getItem(CACHE_KEY)||'{}');
      $('#displayName').textContent=u?.name||u?.username||u?.email||cache.name||'User';
      $('#profilePic').src=u?.profile_picture||u?.picture||u?.avatar||cache.profile_picture||'/images/default-avatar.png';
      $('#logoutBtn').onclick=()=>{localStorage.removeItem(TOKEN_KEY);localStorage.removeItem(CACHE_KEY);location.replace('/login')}
    });

    const API = {
      list:  ()=>fetch('/api/cms_class_list.php',{headers:{Authorization:'Bearer '+jwt}}).then(r=>r.json()),
      get:   (id)=>fetch('/api/cms_class_get.php?id='+encodeURIComponent(id),{headers:{Authorization:'Bearer '+jwt}}).then(r=>r.json()),
      saveMeta:(payload)=>fetch('/api/cms_class_save.php',{method:'POST',headers:{'Content-Type':'application/json',Authorization:'Bearer '+jwt},body:JSON.stringify(payload)}).then(r=>r.json()),
      saveStudents:(payload)=>fetch('/api/cms_student_save.php',{method:'POST',headers:{'Content-Type':'application/json',Authorization:'Bearer '+jwt},body:JSON.stringify(payload)}).then(r=>r.json())
    };

    API.delete = (id)=>fetch('/api/cms_class_delete.php',{
      method:'POST',
      headers:{'Content-Type':'application/json', Authorization:'Bearer '+jwt},
      body: JSON.stringify({ class_id:id })
    }).then(r=>r.json());
    
    $('#btnDeleteClass').onclick = async ()=>{
      if(!currentClassId) return;
      if(!confirm('Delete this class and all its students? This cannot be undone.')) return;
      const res = await API.delete(currentClassId);
      if(res.status==='success'){
        toast('Class deleted');
        currentClassId = null;
        showGrid();
        loadGrid();
      } else toast(res.error||'Delete failed','err');
    };

    const gridView = $('#gridView');
    const classView = $('#classView');
    const btnNewClass = $('#btnNewClass');
    const btnBack = $('#btnBackToGrid');
    const stuBody = $('#stuBody');
    const msg = $('#msg');

    let currentClassId = null;
    let clsLogoData = '';

    const COLORS = ['#ef4444','#f97316','#f59e0b','#10b981','#22c55e','#06b6d4','#3b82f6','#8b5cf6','#ec4899','#64748b'];

    const showGrid = ()=>{ classView.classList.add('hidden'); gridView.classList.remove('hidden'); btnBack.classList.add('hidden'); };
    const showClass= ()=>{ gridView.classList.add('hidden'); classView.classList.remove('hidden'); btnBack.classList.remove('hidden'); };

    // const toastA = (t,ok=true)=>{ msg.textContent=t; msg.style.color=ok?'var(--ok)':'var(--err)' };

    function cardTemplate(c){
      const bg = c.color || COLORS[c.id % COLORS.length] || '#3b82f6';
      const init = (c.name||'')[0]?.toUpperCase() || 'C';
      const logo = c.logo_url ? `<img src="${c.logo_url}" class="w-11 h-11 rounded-lg border object-cover">` : `<div class="teams-logo" style="background:${bg}">${init}</div>`;
      return `
        <div class="teams-card" data-id="${c.id}">
          <div class="flex items-start justify-between">
            <div class="flex items-center gap-3">
              ${logo}
              <div>
                <div class="font-bold">${c.name||'Untitled Class'}</div>
                <div class="muted text-sm">${c.section||''}</div>
              </div>
            </div>
            <div class="teams-actions text-gray-500 text-sm flex gap-3">
              <span class="pill">${(c.students_count||0)} students</span>
            </div>
          </div>
          <div class="muted text-xs flex gap-2">
            <span>${c.teacher_name||''}</span> · <span>${c.exam_title||''}</span>
          </div>
        </div>`;
    }

    async function loadGrid(){
      gridView.innerHTML = '';
      const res = await API.list();
      const items = res?.items || [];
      if(!items.length){
        gridView.innerHTML = `<div class="col-span-full card text-center">
          <div class="text-lg font-bold mb-1">No classes yet</div>
          <div class="muted mb-3">Click “Create Class” to add your first class.</div>
          <button class="btn" id="xNewFromEmpty"><i class="fa fa-plus mr-2"></i>Create Class</button>
        </div>`;
        $('#xNewFromEmpty')?.addEventListener('click', createClassFlow);
        return;
      }
      gridView.innerHTML = items.map(cardTemplate).join('');
      $$('.teams-card', gridView).forEach(card=>{
        card.addEventListener('click',()=> openClass(card.getAttribute('data-id')));
      });
    }

    function mkRow(d={}){
  const classSection = $('#clsSection').value.trim();
  const tr=document.createElement('tr');
  tr.innerHTML=`
    <td><button type="button" class="text-red-600 hover:underline" title="Remove">×</button></td>
    <td contenteditable="true">${d.student_name||''}</td>
    <td contenteditable="true">${(d.section||classSection||'')}</td>
    <td contenteditable="true">${d.grade||''}</td>
    <td contenteditable="true">${d.score||''}</td>
    <td contenteditable="true">${d.date||''}</td>
    <td contenteditable="true">${d.page_no||''}</td>
    <td contenteditable="true">${d.set_no||''}</td>
    <td contenteditable="true">${d.exam_title||$('#clsExamTitle').value||''}</td>`;
  tr.querySelector('button').onclick=()=>tr.remove();
  return tr;
}
            $('#clsSection').addEventListener('input', e=>{
  const v = e.target.value.trim();
  Array.from(stuBody.querySelectorAll('tr')).forEach(tr=>{
    const cell = tr.children[2]; // section col
    if(!cell.textContent.trim()) cell.textContent = v;
  });
});


    async function openClass(id){
      const data = await API.get(id);
      if(data.status!=='success'){ alert(data.error||'Failed to load class'); return }
      currentClassId = data.class.id;

      // Fill meta
      $('#clsName').value = data.class.name || '';
      $('#clsSection').value = data.class.section || '';
      $('#clsTeacher').value = data.class.teacher_name || '';
      $('#clsExamTitle').value = data.class.exam_title || '';
      $('#clsHeader').value = data.class.header_text || '';
      $('#clsLogo').src = data.class.logo_url || '/images/icon.png';
      $('#clsInfo').textContent = `${data.students.length} students`;

      // Fill table
      stuBody.innerHTML = '';
      data.students.forEach(s=>stuBody.appendChild(mkRow(s)));
      if(!data.students.length) stuBody.appendChild(mkRow({}));

      showClass();
    }

    async function createClassFlow(){
      // minimal create: save meta first then open
      const payload = { class_id: null, name: 'New Class', section: '', teacher_name:'', exam_title:'', header_text:'', logo_data_url:'' };
      const res = await API.saveMeta(payload);
      if(res.status==='success'){ await loadGrid(); await openClass(res.class_id); }
      else alert(res.error||'Failed to create class');
    }

    // Events
    btnNewClass.onclick = createClassFlow;
    btnBack.onclick = showGrid;
    $('#btnAddStudent').onclick = ()=> stuBody.appendChild(mkRow({}));

    $('#clsLogoInput').addEventListener('change',e=>{
      const f=e.target.files?.[0]; if(!f) return;
      const r=new FileReader(); r.onload=()=>{clsLogoData=r.result; $('#clsLogo').src=r.result}; r.readAsDataURL(f);
    });
/*
    $('#btnSaveClassMeta').onclick = async ()=>{
      const payload = {
        class_id: currentClassId,
        name: $('#clsName').value.trim(),
        section: $('#clsSection').value.trim(),
        teacher_name: $('#clsTeacher').value.trim(),
        exam_title: $('#clsExamTitle').value.trim(),
        header_text: $('#clsHeader').value.trim(),
        logo_data_url: clsLogoData || ''
      };
      const res = await API.saveMeta(payload);
      if(res.status==='success'){ toast('Class saved.'); clsLogoData=''; loadGrid(); }
      else toast(res.error||'Save failed', false);
    };
    */
    
    $('#btnSaveClassMeta').onclick = async ()=>{
  const payload = {
    class_id: currentClassId,
    name: $('#clsName').value.trim(),
    section: $('#clsSection').value.trim(),
    teacher_name: $('#clsTeacher').value.trim(),
    exam_title: $('#clsExamTitle').value.trim(),
    header_text: $('#clsHeader').value.trim(),
    logo_data_url: clsLogoData || ''
  };
  const res = await API.saveMeta(payload);
  if(res.status==='success'){
    toast('Class saved'); 
    clsLogoData=''; 
    if(!currentClassId) currentClassId = res.class_id;
    loadGrid();
  } else toast(res.error||'Save failed','err');
};

    /*
    $('#btnSaveStudents').onclick = async ()=>{
      const rows = Array.from(stuBody.querySelectorAll('tr')).map(tr=>({
        student_name: tr.children[1].textContent.trim(),
        section: tr.children[2].textContent.trim(),
        grade: tr.children[3].textContent.trim(),
        score: tr.children[4].textContent.trim(),
        date: tr.children[5].textContent.trim(),
        page_no: tr.children[6].textContent.trim(),
        set_no: tr.children[7].textContent.trim(),
        exam_title: tr.children[8].textContent.trim()
      })).filter(r=>r.student_name);
      const res = await API.saveStudents({ class_id: currentClassId, rows });
      if(res.status==='success'){ toast('Students saved.'); $('#clsInfo').textContent=`${rows.length} students`; loadGrid(); }
      else toast(res.error||'Save failed', false);
    };
    */
    $('#btnSaveStudents').onclick = async ()=>{
  const clsSection = $('#clsSection').value.trim();
  const rows = Array.from(stuBody.querySelectorAll('tr')).map(tr=>({
    student_name: tr.children[1].textContent.trim(),
    section: (tr.children[2].textContent.trim() || clsSection),
    grade: tr.children[3].textContent.trim(),
    score: tr.children[4].textContent.trim(),
    date: tr.children[5].textContent.trim(),
    page_no: tr.children[6].textContent.trim(),
    set_no: tr.children[7].textContent.trim(),
    exam_title: tr.children[8].textContent.trim() || $('#clsExamTitle').value.trim()
  })).filter(r=>r.student_name);

  const res = await API.saveStudents({ class_id: currentClassId, rows });
  if(res.status==='success'){ 
    toast('Students saved'); 
    document.getElementById('clsInfo').textContent=`${rows.length} students`; 
    loadGrid(); 
  } else toast(res.error||'Save failed', 'err');
};


    // Simple print (same fix—no inline <script> in string)
    $('#btnPrint').onclick = ()=>{
      const rows = Array.from(stuBody.querySelectorAll('tr')).map(tr=>({
        name: tr.children[1].textContent.trim(),
        section: tr.children[2].textContent.trim(),
        grade: tr.children[3].textContent.trim(),
        score: tr.children[4].textContent.trim(),
        date: tr.children[5].textContent.trim(),
        page: tr.children[6].textContent.trim(),
        set: tr.children[7].textContent.trim(),
        title: tr.children[8].textContent.trim()
      }));
      const w=window.open('','_blank');
      const html=`<!doctype html><html><head><meta charset="utf-8"><title>Roster</title>
      <style>body{font-family:Arial,system-ui,sans-serif;margin:24px;color:#111}
      .hdr{display:flex;gap:12px;align-items:center;margin-bottom:12px}
      .hdr img{width:48px;height:48px;object-fit:contain;border:1px solid #e5e7eb;border-radius:8px}
      h1{margin:0 0 4px;font-size:20px}.muted{color:#64748b;font-size:12px}
      table{width:100%;border-collapse:collapse;margin-top:8px}th,td{border:1px solid #e5e7eb;padding:8px 10px;font-size:12px}th{background:#f8fafc;text-align:left}</style>
      </head><body>
      <div class="hdr"><img src="${$('#clsLogo').src}"><div>
        <div class="muted">${($('#clsHeader').value||'')}</div>
        <h1>${($('#clsExamTitle').value||'Exam')}</h1>
        <div class="muted">Teacher: ${($('#clsTeacher').value||'')}</div></div></div>
      <table><thead><tr><th>#</th><th>Student Name</th><th>Section</th><th>Grade</th><th>Score</th><th>Date</th><th>Page</th><th>Set</th><th>Exam Title</th></tr></thead>
      <tbody>${rows.map((r,i)=>`<tr><td>${i+1}</td><td>${r.name}</td><td>${r.section}</td><td>${r.grade}</td><td>${r.score}</td><td>${r.date}</td><td>${r.page}</td><td>${r.set}</td><td>${r.title}</td></tr>`).join('')}</tbody></table>
      </body></html>`;
      w.document.write(html); w.document.close(); w.onload=()=>{w.focus(); setTimeout(()=>w.print(),50)}
    };

    // boot
    loadGrid();
  </script>
</body>
</html>
