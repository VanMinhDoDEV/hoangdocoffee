<style>
  :root { --cta-primary: #ffa31a; }
  .cta-tooltip-text{animation:slideIn .3s ease-out}
  @keyframes slideIn{from{opacity:0;transform:translateX(10px)}to{opacity:1;transform:translateX(0)}}
  .cta-disclaimer-box{animation:slideUp .4s ease-out}
  @keyframes slideUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
  .cta-float-btn:hover{transform:scale(1.1)}
  .cta-float-btn{transition:all .3s ease}
  .cta-fixed{position:fixed;right:24px;bottom:24px;z-index:50}
  .cta-child{display:flex;flex-direction:column;gap:12px;margin-bottom:12px;opacity:0;pointer-events:none;transition:all .3s ease}
  .cta-child.open{opacity:1;pointer-events:auto}
  .cta-btn{width:56px;height:56px;border-radius:9999px;display:flex;align-items:center;justify-content:center;box-shadow:0 10px 15px -3px rgba(0,0,0,.1),0 4px 6px -4px rgba(0,0,0,.1)}
  .cta-btn-main{width:64px;height:64px}
  .cta-scale-0{transform:scale(0)}
  .cta-scale-100{transform:scale(1)}
  .cta-tooltip{position:absolute;right:64px;background:#1f2937;color:#fff;padding:6px 12px;border-radius:8px;font-size:13px;white-space:nowrap;opacity:0;transition:opacity .2s ease;pointer-events:none}
  .cta-group:hover .cta-tooltip{opacity:1}
  .cta-disclaimer{position:fixed;right:24px;bottom:128px;background:#fff;border-radius:12px;box-shadow:0 25px 50px -12px rgba(0,0,0,.25);padding:16px;max-width:320px;z-index:40}
  .cta-disclaimer.hidden{display:none}
  .cta-close{position:absolute;right:-8px;top:-8px;background:var(--cta-primary, #ffa31a);color:#fff;border-radius:9999px;width:24px;height:24px;display:flex;align-items:center;justify-content:center;font-size:14px}
</style>
<div class="cta-disclaimer cta-disclaimer-box hidden" id="ctaDisclaimer">
  <button class="cta-close" id="ctaClose">×</button>
  <div style="display:flex;gap:12px;align-items:flex-start">
    <svg style="width:20px;height:20px;color:#2563eb" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <p id="ctaDisclaimerText" style="margin:0;font-size:14px;color:#374151;line-height:1.5">Dây là dữ liệu (sản phẩm) demo, chúng tôi kinh doanh website không kinh doanh các sản phẩm trên, có thắc mắc gì xin liên hệ</p>
  </div>
  </div>
<div class="cta-fixed">
  <div id="ctaChildButtons" class="cta-child">
    <a id="ctaZalo" href="https://zalo.me/0123456789" target="_blank" rel="noopener noreferrer" class="cta-float-btn cta-btn cta-scale-0 cta-group" style="background:var(--cta-primary, #ffa31a);color:#fff;position:relative">
      <svg style="width:28px;height:28px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 460.1 436.6" fill="currentColor"><path d="M82.6 380.9c-1.8-.8-3.1-1.7-1-3.5 1.3-1 2.7-1.9 4.1-2.8 13.1-8.5 25.4-17.8 33.5-31.5 6.8-11.4 5.7-18.1-2.8-26.5C69 269.2 48.2 212.5 58.6 145.5 64.5 107.7 81.8 75 107 46.6c15.2-17.2 33.3-31.1 53.1-42.7 1.2-.7 2.9-.9 3.1-2.7-.4-1-1.1-.7-1.7-.7-33.7 0-67.4-.7-101 .2C28.3 1.7.5 26.6.6 62.3c.2 104.3 0 208.6 0 313 0 32.4 24.7 59.5 57 60.7 27.3 1.1 54.6.2 82 .1 2 .1 4 .2 6 .2H290c36 0 72 .2 108 0 33.4 0 60.5-27 60.5-60.3v-.6-58.5c0-1.4.5-2.9-.4-4.4-1.8.1-2.5 1.6-3.5 2.6-19.4 19.5-42.3 35.2-67.4 46.3-61.5 27.1-124.1 29-187.6 7.2-5.5-2-11.5-2.2-17.2-.8-8.4 2.1-16.7 4.6-25 7.1-24.4 7.6-49.3 11-74.8 6zm72.5-168.5c1.7-2.2 2.6-3.5 3.6-4.8 13.1-16.6 26.2-33.2 39.3-49.9 3.8-4.8 7.6-9.7 10-15.5 2.8-6.6-.2-12.8-7-15.2-3-.9-6.2-1.3-9.4-1.1-17.8-.1-35.7-.1-53.5 0-2.5 0-5 .3-7.4.9-5.6 1.4-9 7.1-7.6 12.8 1 3.8 4 6.8 7.8 7.7 2.4.6 4.9.9 7.4.8 10.8.1 21.7 0 32.5.1 1.2 0 2.7-.8 3.6 1-.9 1.2-1.8 2.4-2.7 3.5-15.5 19.6-30.9 39.3-46.4 58.9-3.8 4.9-5.8 10.3-3 16.3s8.5 7.1 14.3 7.5c4.6.3 9.3.1 14 .1 16.2 0 32.3.1 48.5-.1 8.6-.1 13.2-5.3 12.3-13.3-.7-6.3-5-9.6-13-9.7-14.1-.1-28.2 0-43.3 0zm116-52.6c-12.5-10.9-26.3-11.6-39.8-3.6-16.4 9.6-22.4 25.3-20.4 43.5 1.9 17 9.3 30.9 27.1 36.6 11.1 3.6 21.4 2.3 30.5-5.1 2.4-1.9 3.1-1.5 4.8.6 3.3 4.2 9 5.8 14 3.9 5-1.5 8.3-6.1 8.3-11.3.1-20 .2-40 0-60-.1-8-7.6-13.1-15.4-11.5-4.3.9-6.7 3.8-9.1 6.9zm69.3 37.1c-.4 25 20.3 43.9 46.3 41.3 23.9-2.4 39.4-20.3 38.6-45.6-.8-25-19.4-42.1-44.9-41.3-23.9.7-40.8 19.9-40 45.6zm-8.8-19.9c0-15.7.1-31.3 0-47 0-8-5.1-13-12.7-12.9-7.4.1-12.3 5.1-12.4 12.8-.1 4.7 0 9.3 0 14v79.5c0 6.2 3.8 11.6 8.8 12.9 6.9 1.9 14-2.2 15.8-9.1.3-1.2.5-2.4.4-3.7.2-15.5.1-31 .1-46.5z"/></svg>
      <span class="cta-tooltip cta-tooltip-text">Chat Zalo</span>
    </a>
    <a id="ctaPhone" href="tel:0123456789" class="cta-float-btn cta-btn cta-scale-0 cta-group" style="background:var(--cta-primary, #ffa31a);color:#fff;position:relative">
      <svg style="width:28px;height:28px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
      <span class="cta-tooltip cta-tooltip-text">Gọi điện</span>
    </a>
  </div>
  <button id="ctaMainBtn" class="cta-float-btn cta-btn cta-btn-main cta-group" style="background:var(--cta-primary, #ffa31a);color:#fff;position:relative">
    <svg id="ctaMainIcon" style="width:32px;height:32px;transition:transform .3s ease" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
    <span class="cta-tooltip cta-tooltip-text">Liên hệ</span>
  </button>
</div>
<script>
  const CTA_SESSION_KEY='cta_disclaimer_shown';
  function ctaShowDisclaimer(){document.getElementById('ctaDisclaimer').classList.remove('hidden');setTimeout(ctaHideDisclaimer,15000)}
  function ctaHideDisclaimer(){document.getElementById('ctaDisclaimer').classList.add('hidden');sessionStorage.setItem(CTA_SESSION_KEY,'true')}
  function ctaInitDisclaimer(){if(!sessionStorage.getItem(CTA_SESSION_KEY)){setTimeout(ctaShowDisclaimer,15000)}document.getElementById('ctaClose').addEventListener('click',ctaHideDisclaimer)}
  function ctaToggleMenu(){const child=document.getElementById('ctaChildButtons');const mainIcon=document.getElementById('ctaMainIcon');const zalo=document.getElementById('ctaZalo');const phone=document.getElementById('ctaPhone');const isOpen=child.classList.contains('open');if(isOpen){child.classList.remove('open');zalo.classList.remove('cta-scale-100');zalo.classList.add('cta-scale-0');phone.classList.remove('cta-scale-100');phone.classList.add('cta-scale-0');mainIcon.style.transform='rotate(0deg)'}else{child.classList.add('open');setTimeout(function(){phone.classList.remove('cta-scale-0');phone.classList.add('cta-scale-100')},50);setTimeout(function(){zalo.classList.remove('cta-scale-0');zalo.classList.add('cta-scale-100')},100);mainIcon.style.transform='rotate(45deg)'}}
  document.getElementById('ctaMainBtn').addEventListener('click',ctaToggleMenu);
  ctaInitDisclaimer();
</script>
