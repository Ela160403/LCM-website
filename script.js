(function(){
  // Defensive: only run when DOM is ready and blocks exist
  function init(){
    const blocks = document.querySelectorAll('.block');
    if (!blocks || blocks.length === 0) return;

    // Prepare animation state
    blocks.forEach(b=>{ b.style.opacity = 0; b.style.transform = 'translateY(8px)'; b.style.transition = 'transform 320ms ease, opacity 320ms ease'; });

    // Stagger reveal
    Array.from(blocks).forEach((b, i) => setTimeout(()=>{ b.style.opacity = 1; b.style.transform = 'translateY(0)'; }, i * 80));

    // Hover lift
    blocks.forEach(b=>{
      b.addEventListener('mouseenter', ()=> b.style.transform = 'translateY(-6px)');
      b.addEventListener('mouseleave', ()=> b.style.transform = 'translateY(0)');
    });
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
})();
