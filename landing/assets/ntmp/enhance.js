(() => {
  'use strict';
  const ar = document.documentElement.lang === 'ar';
  const reduced = matchMedia('(prefers-reduced-motion: reduce)');
  const header = document.querySelector('header');
  const progress = document.createElement('div'); progress.className = 'reading-progress'; progress.setAttribute('aria-hidden','true'); document.body.append(progress);
  const top = document.createElement('button'); top.type = 'button'; top.className = 'back-to-top'; top.textContent = '↑'; top.hidden = true; top.setAttribute('aria-label',ar ? 'العودة إلى الأعلى' : 'Back to top'); document.body.append(top);
  top.addEventListener('click',()=> {window.scrollTo({top:0,behavior:reduced.matches?'auto':'smooth'}); document.querySelector('.brand').focus({preventScroll:true});});
  let scheduled = false;
  const mobile = document.createElement('aside'); mobile.className = 'mobile-review'; mobile.hidden = true;
  const label = document.createElement('span'); label.textContent = ar?'جاهزية أوضح لمنشأتك':'A clearer next step for your hotel';
  const action = document.createElement('a'); action.className = 'btn'; action.href = '#review'; action.textContent = ar?'اطلب مراجعة ↗':'Free readiness review ↗'; mobile.append(label,action); document.body.append(mobile);
  let reviewVisible = false;
  const update = () => {
    scheduled = false;
    const y = window.scrollY; const distance = document.documentElement.scrollHeight-innerHeight;
    progress.style.transform = `scaleX(${distance>0?Math.min(1,y/distance):0})`;
    header.classList.toggle('is-scrolled',y>12); top.hidden = y<900;
    mobile.hidden = !matchMedia('(max-width:760px)').matches || y<500 || reviewVisible;
  };
  window.addEventListener('scroll',()=>{if(!scheduled){scheduled=true;requestAnimationFrame(update);}}, {passive:true});
  window.addEventListener('resize',update,{passive:true}); update();
  if('IntersectionObserver' in window){
    const sections = new IntersectionObserver(entries=>{for(const e of entries){if(e.isIntersecting){e.target.classList.add('is-revealed');sections.unobserve(e.target);}}},{threshold:.07,rootMargin:'0px 0px -20px 0px'});
    if(!reduced.matches) document.querySelectorAll('.problem-half,.timeline li,.capabilities article,.demo,.qualification,.faq,.footer-top').forEach((el,i)=>{el.style.setProperty('--reveal-delay',`${(i%4)*55}ms`);el.classList.add('reveal-ready');sections.observe(el);});
    const reviewObserver = new IntersectionObserver(entries=>{reviewVisible=entries.some(e=>e.isIntersecting);update();},{threshold:0});reviewObserver.observe(document.querySelector('#review'));
    const navObserver = new IntersectionObserver(entries=>{for(const e of entries)if(e.isIntersecting){document.querySelectorAll('header nav a[href^="#"]').forEach(a=>{if(a.hash==='#'+e.target.id)a.setAttribute('aria-current','location');else a.removeAttribute('aria-current');});}},{rootMargin:'-15% 0px -55% 0px'});
    document.querySelectorAll('#workflow,#demo').forEach(s=>navObserver.observe(s));
  }
  // All answers start visible for people and SEO; visitors can collapse them.
  document.querySelectorAll('.faq-item').forEach((item,i)=>{
    const question = item.querySelector('.faq-question'); const answer = item.querySelector('.faq-answer');
    answer.id = `faq-answer-${i}`;
    const button = document.createElement('button');button.type='button';button.className='faq-toggle';button.setAttribute('aria-expanded','true');button.setAttribute('aria-controls',answer.id);
    question.replaceWith(button); button.append(question);
    button.addEventListener('click',()=>{const open=button.getAttribute('aria-expanded')==='true';button.setAttribute('aria-expanded',String(!open));answer.hidden=open;if(!open){answer.classList.remove('answer-enter');void answer.offsetWidth;answer.classList.add('answer-enter');}});
  });
  const dialog = document.createElement('dialog');dialog.className='ntmp-dialog';dialog.setAttribute('aria-labelledby','preview-title');
  const close = document.createElement('button'); close.type='button';close.className='dialog-close';close.textContent='×';close.setAttribute('aria-label',ar?'إغلاق':'Close');
  const title = document.createElement('h2');title.id='preview-title';title.textContent=ar?'معاينة توضيحية للإجراءات':'Explore the workflow layout';
  const note = document.createElement('p');note.textContent=ar?'هذا تصور توضيحي للتصميم وليس شاشة منتج أو دليلًا على التفعيل. نراجع الإمكانات المناسبة لمنشأتك خلال العرض.':'This is an illustrative layout, not a product screenshot or proof of enablement. Your property’s supported capabilities are reviewed during a guided demo.';
  const body = document.createElement('div'); const demo = document.createElement('a');demo.className='btn';demo.href='#review';demo.textContent=ar?'اطلب عرضًا لمنشأتك ↗':'Request your property walkthrough ↗';
  dialog.append(close,title,note,body,demo); document.body.append(dialog);
  close.addEventListener('click',()=>dialog.close());demo.addEventListener('click',()=>dialog.close());
  dialog.addEventListener('click',e=>{if(e.target===dialog){const r=dialog.getBoundingClientRect();if(e.clientX<r.left||e.clientX>r.right||e.clientY<r.top||e.clientY>r.bottom)dialog.close();}});
  document.querySelectorAll('.proof').forEach(proof=>{
    proof.tabIndex=0;proof.setAttribute('role','button');proof.setAttribute('aria-haspopup','dialog');proof.setAttribute('aria-label',(ar?'تكبير التصور التوضيحي: ':'Enlarge illustrative layout: ')+proof.querySelector('strong').textContent.trim());
    const open=()=>{body.replaceChildren(proof.querySelector('.mock-screen').cloneNode(true));dialog.showModal();};
    proof.addEventListener('click',open);proof.addEventListener('keydown',e=>{if(e.key==='Enter'||e.key===' '){e.preventDefault();open();}});
  });
})();
