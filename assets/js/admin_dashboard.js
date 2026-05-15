function showSection(name,el){
  document.querySelectorAll('.section').forEach(s=>s.classList.remove('active'));
  document.querySelectorAll('.nav-item').forEach(n=>n.classList.remove('active'));
  document.getElementById('sec-'+name).classList.add('active');
  if(el)el.classList.add('active');
  return false;
}
function showToast(msg,type='success'){
  const t=document.getElementById('toast');
  t.innerHTML=`<i class="fas fa-${type==='success'?'check-circle':'circle-xmark'}"></i> ${msg}`;
  t.className=`toast show ${type}`;
  setTimeout(()=>t.classList.remove('show'),3000);
}
function filterTable(inputId,tableId){
  const q=document.getElementById(inputId).value.toLowerCase();
  document.querySelectorAll(`#${tableId} tbody tr`).forEach(row=>{
    row.style.display=row.textContent.toLowerCase().includes(q)?'':'none';
  });
}
let isEdit=false;
function openAddModal(){
  isEdit=false;
  document.getElementById('modalTitle').innerHTML='<i class="fas fa-plus"></i> Add Book';
  document.getElementById('modalSubmitBtn').innerHTML='<i class="fas fa-save"></i> Add Book';
  document.getElementById('bookForm').reset();
  document.getElementById('availableGroup').style.display='none';
  document.getElementById('bookModal').classList.add('open');
}
function openEditModal(book){
  isEdit=true;
  document.getElementById('modalTitle').innerHTML='<i class="fas fa-pen"></i> Edit Book';
  document.getElementById('modalSubmitBtn').innerHTML='<i class="fas fa-save"></i> Save Changes';
  document.getElementById('bookId').value=book.id;
  document.getElementById('fTitle').value=book.title;
  document.getElementById('fAuthor').value=book.author;
  document.getElementById('fCategory').value=book.category??'';
  document.getElementById('fCallNumber').value=book.call_number??'';
  document.getElementById('fIsbn').value=book.isbn??'';
  document.getElementById('fQuantity').value=book.quantity;
  document.getElementById('fAvailable').value=book.available;
  document.getElementById('availableGroup').style.display='block';
  document.getElementById('bookModal').classList.add('open');
}
function closeModal(){document.getElementById('bookModal').classList.remove('open');}
document.getElementById('bookForm').addEventListener('submit',async function(e){
  e.preventDefault();
  const fd=new FormData(this);
  const url=isEdit?'../backend/update_book.php':'../backend/add_book.php';
  const res=await fetch(url,{method:'POST',body:fd});
  const data=await res.json();
  if(data.success){showToast(data.message);closeModal();setTimeout(()=>location.reload(),1000);}
  else{showToast(data.message,'error');}
});
async function deleteBook(id,btn){
  if(!confirm('Delete this book? This cannot be undone.'))return;
  btn.disabled=true;
  const fd=new FormData();fd.append('id',id);
  const res=await fetch('../backend/delete_book.php',{method:'POST',body:fd});
  const data=await res.json();
  if(data.success){showToast(data.message);btn.closest('tr').remove();}
  else{showToast(data.message,'error');btn.disabled=false;}
}
document.getElementById('bookModal').addEventListener('click',function(e){if(e.target===this)closeModal();});