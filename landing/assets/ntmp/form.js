(() => {
  'use strict';
  const form=document.querySelector('#review-form');
  if(!form)return;
  const ar=document.documentElement.lang==='ar';
  const first=form.querySelector('#essential'),second=form.querySelector('#needs'),card=form.closest('.form-card');
  const status=form.querySelector('#form-status');
  const txt=ar?{
    required:'يرجى إكمال هذا الحقل.',invalid:'يرجى التحقق من القيمة المدخلة.',email:'أدخل بريدًا إلكترونيًا صحيحًا.',phone:'أدخل رقم عمل صحيحًا، مثل 05XXXXXXXX أو رقم دولي يبدأ بـ +.',
    sending:'جارٍ إرسال طلبك…',failed:'تعذّر تأكيد إرسال طلبك. بياناتك محفوظة في هذه الصفحة؛ حاول مرة أخرى أو استخدم صفحة التواصل الرسمية.',rate:'طلبات كثيرة حاليًا. يرجى المحاولة بعد بضع دقائق.',restricted:'تعذّر استقبال الطلب عبر هذه القناة. يُرجى التواصل مع الشركة مباشرة.',
    thanks:'شكرًا لك.',success:'سيراجع فريقنا طلبك ويتواصل معك خلال ساعات العمل. للاستفسارات العاجلة استخدم قناة التواصل الرسمية الموثقة.',contact:'قناة التواصل الرسمية',
    analyticsTitle:'اختيار إحصاءات الاستخدام',analyticsText:'السماح بإحصاءات التصفح والتفاعل دون تضمين بيانات النموذج. لا يؤثر اختيارك على إرسال الطلب. يمكنك تغيير اختيارك هنا في أي وقت.',allow:'السماح بالإحصاءات',deny:'إيقاف الإحصاءات',close:'إغلاق',
  }:{
    required:'Please complete this field.',invalid:'Please check this value.',email:'Enter a valid work email address.',phone:'Enter a valid work number, such as 05XXXXXXXX or an international number starting with +.',
    sending:'Sending your request…',failed:'We could not confirm delivery. Your details remain on this page; try again or use the official contact page.',rate:'Too many requests right now. Please try again in a few minutes.',restricted:'This request could not be accepted through this channel. Please contact the company directly.',
    thanks:'Thank you.',success:'Our team will review your request and contact you during business hours. For urgent questions use the verified company contact channel.',contact:'Official company contact',
    analyticsTitle:'Your analytics preference',analyticsText:'Allow page and interaction analytics without form values. Your choice does not affect your request. You can change it here at any time.',allow:'Allow analytics',deny:'Keep analytics off',close:'Close',
  };
  const privacyBlocked=()=>navigator.globalPrivacyControl||navigator.doNotTrack==='1';
  let analytics=false,started=false,sending=false,completed=false;
  const uuid=()=>crypto.randomUUID?crypto.randomUUID():Array.from(crypto.getRandomValues(new Uint8Array(16)),b=>b.toString(16).padStart(2,'0')).join('');
  const submissionId=uuid();
  try{analytics=localStorage.getItem('fq_ntmp_analytics')==='granted';localStorage.setItem('fq_ntmp_locale',ar?'ar':'en');}catch(_){}
  const seen=new Set();
  function track(event,id){
    if(!analytics||privacyBlocked())return;
    if(id&&seen.has(event+id))return;
    if(id)seen.add(event+id);
    window.dataLayer=window.dataLayer||[];
    window.dataLayer.push({event,page_language:ar?'ar':'en',...(id?{event_id:id}:{})});
  }
  track('ntmp_landing_view');
  const query=new URLSearchParams(location.search);
  for(const key of ['utm_source','utm_medium','utm_campaign','utm_content','utm_term']){
    const value=query.get(key);if(!value)continue;const input=document.createElement('input');input.type='hidden';input.name=key;input.value=value.slice(0,200);form.append(input);
  }
  const values={role:['owner','gm','finance','it','other'],hotel_type:['hotel','serviced','other'],rooms:['under20','20-49','50-99','100+'],ntmp_status:['connected','errors','not_connected','not_sure'],priority:['operations','financial','both'],rollout:['0-30','31-90','later','research']};
  for(const [name,options]of Object.entries(values))form.querySelectorAll(`[name="${name}"] option`).forEach((option,i)=>option.value=i?options[i-1]:'');
  form.querySelectorAll('input:not([type=hidden]),select').forEach((field,i)=>{
    if(field.name==='website')return;
    field.id=field.id||`review-field-${i}`;
    const feedback=document.createElement('small');feedback.id=field.id+'-feedback';feedback.className='field-feedback';field.setAttribute('aria-describedby',feedback.id);field.closest('label').append(feedback);
    field.addEventListener('input',()=>{if(field.getAttribute('aria-invalid')==='true')check(field);status.textContent='';});
    field.addEventListener('change',()=>{if(field.getAttribute('aria-invalid')==='true')check(field);});
    if(!['checkbox','number'].includes(field.type))field.addEventListener('blur',()=>{if(field.value)check(field);});
  });
  function phone(raw){let v=raw.replace(/[٠-٩۰-۹]/g,d=>String(d.charCodeAt(0)&15)).replace(/[\s().-]/g,'');if(/^05\d{8}$/.test(v))v='+966'+v.slice(1);else if(/^5\d{8}$/.test(v))v='+966'+v;else if(/^00966/.test(v))v='+'+v.slice(2);else if(/^9665\d{8}$/.test(v))v='+'+v;return /^\+[1-9]\d{7,14}$/.test(v)?v:null;}
  function check(field){
    if(field.name==='website'||field.type==='hidden')return true;
    let message='';const value=field.value.trim();
    if(field.required&&(field.type==='checkbox'?!field.checked:!value))message=txt.required;
    else if(field.name==='email'&&(!field.validity.valid||!/^\S+@\S+\.\S+$/.test(value)))message=txt.email;
    else if(field.name==='phone'&&!phone(value))message=txt.phone;
    else if(['name','property','city'].includes(field.name)&&value.length<2)message=txt.invalid;
    else if(!field.validity.valid)message=txt.invalid;
    field.setAttribute('aria-invalid',String(Boolean(message)));
    const feedback=document.getElementById(field.id+'-feedback');if(feedback)feedback.textContent=message;
    return !message;
  }
  function valid(panel){let failed=null;panel.querySelectorAll('input,select').forEach(field=>{if(!check(field)&&!failed)failed=field;});if(failed){failed.focus();return false;}return true;}
  function step(two){
    first.hidden=two;second.hidden=!two;card.classList.toggle('on-step-two',two);
    const visible=two?second:first;visible.classList.remove('step-enter');void visible.offsetWidth;visible.classList.add('step-enter');
    for(const [id,active]of [['step-one',!two],['step-two',two]]){const el=document.getElementById(id);el.classList.toggle('active',active);if(active)el.setAttribute('aria-current','step');else el.removeAttribute('aria-current');}
    visible.querySelector('input,select').focus({preventScroll:true});
    if(card.getBoundingClientRect().top<90)card.scrollIntoView({behavior:matchMedia('(prefers-reduced-motion:reduce)').matches?'auto':'smooth',block:'start'});
  }
  document.getElementById('step-one').setAttribute('aria-current','step');
  form.querySelector('.next').addEventListener('click',()=>{if(valid(first))step(true);});
  form.querySelector('.back').addEventListener('click',()=>{if(!sending)step(false);});
  form.addEventListener('focusin',()=>{if(!started){started=true;track('ntmp_review_form_start');}});
  const endpoint=document.querySelector('meta[name="ntmp-review-endpoint"]')?.content;
  function showStatus(message){status.textContent=message;status.tabIndex=-1;status.focus({preventScroll:true});}
  form.addEventListener('submit',async event=>{
    event.preventDefault();if(sending||completed)return;
    if(second.hidden){if(valid(first))step(true);return;}
    if(!valid(first)){step(false);return;}if(!valid(second))return;
    if(!endpoint){showStatus(txt.failed);return;}
    const payload=Object.fromEntries(new FormData(form));payload.phone=phone(payload.phone);payload.consent=form.elements.consent.checked;payload.marketing=form.elements.marketing.checked;payload.analytics_consent=analytics&&!privacyBlocked();payload.submission_id=submissionId;payload.language=ar?'ar':'en';payload.page_url=location.origin+location.pathname;
    const button=form.querySelector('[type=submit]'),caption=button.textContent;const back=form.querySelector('.back');
    sending=true;button.disabled=true;back.disabled=true;button.textContent=txt.sending;form.setAttribute('aria-busy','true');showStatus(txt.sending);
    const abort=new AbortController(),timeout=setTimeout(()=>abort.abort(),18000);
    try{
      const response=await fetch(endpoint,{method:'POST',headers:{'Content-Type':'application/json',Accept:'application/json'},credentials:'same-origin',body:JSON.stringify(payload),signal:abort.signal});
      const result=await response.json();
      if(!response.ok||result.ok!==true){
        if(result.fields){let invalid=null;for(const name of Object.keys(result.fields)){const field=form.elements.namedItem(name);if(field?.id){field.setAttribute('aria-invalid','true');document.getElementById(field.id+'-feedback').textContent=txt.invalid;if(!invalid)invalid=field;}}if(invalid){step(!first.contains(invalid));invalid.focus();}}
        showStatus(response.status===429?txt.rate:response.status===403?txt.restricted:txt.failed);return;
      }
      completed=true;if(!result.duplicate)track('ntmp_review_form_submit_success',result.submission_id||submissionId);
      const done=document.createElement('div');done.className='form-complete';done.tabIndex=-1;done.setAttribute('role','status');
      const mark=document.createElement('span');mark.className='success-mark';mark.textContent='✓';mark.setAttribute('aria-hidden','true');
      const heading=document.createElement('h3');heading.textContent=txt.thanks;const message=document.createElement('p');message.textContent=txt.success;
      const contact=document.createElement('a');contact.href=document.querySelector('.availability a').href;contact.textContent=txt.contact+' ↗';done.append(mark,heading,message,contact);
      form.hidden=true;card.classList.add('is-complete');card.append(done);done.focus();
    }catch(_){showStatus(txt.failed);}finally{clearTimeout(timeout);sending=false;button.disabled=false;back.disabled=false;button.textContent=caption;form.removeAttribute('aria-busy');}
  });
  const settings=document.createElement('dialog');settings.className='ntmp-dialog';settings.setAttribute('aria-labelledby','analytics-title');
  const heading=document.createElement('h2');heading.id='analytics-title';heading.textContent=txt.analyticsTitle;const text=document.createElement('p');text.textContent=txt.analyticsText;
  const close=document.createElement('button');close.type='button';close.className='dialog-close';close.textContent='×';close.setAttribute('aria-label',txt.close);close.addEventListener('click',()=>settings.close());
  const actions=document.createElement('div');actions.className='dialog-actions';
  for(const [value,label]of [[false,txt.deny],[true,txt.allow]]){const b=document.createElement('button');b.type='button';b.className='btn'+(!value?' secondary':'');b.textContent=label;b.addEventListener('click',()=>{analytics=value;try{localStorage.setItem('fq_ntmp_analytics',value?'granted':'denied');}catch(_){}settings.close();});actions.append(b);}
  settings.append(close,heading,text,actions);document.body.append(settings);
  document.getElementById('analytics-choice').addEventListener('click',()=>settings.showModal());
})();
