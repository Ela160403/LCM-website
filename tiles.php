<?php
function findLCM($a, $b) {
    $max = max($a, $b);
    $lcm = $max;
    while (true) {
        if ($lcm % $a == 0 && $lcm % $b == 0) return $lcm;
        $lcm++;
    }
}

$a = isset($_GET['a']) ? max(2, (int)$_GET['a']) : rand(2,8);
$b = isset($_GET['b']) ? max(2, (int)$_GET['b']) : rand(3,10);
$lcm = findLCM($a, $b);

function generateTiles($n, $count=10){
    $tiles = [];
    for($i=1;$i<=$count;$i++) $tiles[] = $n*$i;
    return $tiles;
}

$tilesA = generateTiles($a, 12);
$tilesB = generateTiles($b, 12);
$pool = array_unique(array_merge($tilesA, $tilesB));
sort($pool);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Number Tiles Puzzle - LCM</title>
  <link rel="stylesheet" href="style.css">
  <style>
    /* small overrides for tiles page */
    .page-hero{display:flex;align-items:center;justify-content:space-between;gap:12px}
  </style>
</head>
<body>
  <div class="container">
    <header class="app-header">
      <div class="page-hero">
        <div>
          <h1 style="margin:0">Number Tiles Puzzle</h1>
            <a href="index.php" 
     style="display: inline-flex; align-items: center; padding: 4px 8px; 
            background-color: #e5e7eb; color: #374151; font-weight: 600;
            font-size: 0.85rem; border-radius: 6px; text-decoration: none;
            transition: background-color 0.2s, transform 0.2s;"
     onmouseover="this.style.backgroundColor='#d1d5db'; this.style.transform='translateY(-1px)'"
     onmouseout="this.style.backgroundColor='#e5e7eb'; this.style.transform='none'">
     
     <svg xmlns="http://www.w3.org/2000/svg"
          style="width: 14px; height: 24px; margin-right: 4px;" 
          fill="none" viewBox="0 0 24 24" stroke="currentColor">
       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
             d="M9 14l-4-4m0 0l4-4m-4 4h11a4 4 0 010 8h-1" />
     </svg>
     Back
  </a>

          <p class="sub-title">Drag tiles into lanes for each number. When both lanes show same top number, you found a common multiple.</p>
        </div>
        <div style="text-align:right">
          <div class="small-note">Numbers:</div>
          <div style="font-weight:800;font-size:1.1rem">a = <span class="lcm-highlight"><?= $a ?></span>, b = <span class="lcm-highlight"><?= $b ?></span></div>
        </div>
      </div>
    </header>

    <main class="card" style="margin-top:18px;padding:18px">
      <div class="tiles-container">
        <div class="tiles-top">
            
          <div class="tiles-controls">

            <a href="index.php" 
     style="display: inline-flex; align-items: center; padding: 4px 8px; 
            background-color: #e5e7eb; color: #374151; font-weight: 600;
            font-size: 0.85rem; border-radius: 6px; text-decoration: none;
            transition: background-color 0.2s, transform 0.2s;"
     onmouseover="this.style.backgroundColor='#d1d5db'; this.style.transform='translateY(-1px)'"
     onmouseout="this.style.backgroundColor='#e5e7eb'; this.style.transform='none'">
     
     <svg xmlns="http://www.w3.org/2000/svg"
          style="width: 14px; height: 24px; margin-right: 4px;" 
          fill="none" viewBox="0 0 24 24" stroke="currentColor">
       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
             d="M9 14l-4-4m0 0l4-4m-4 4h11a4 4 0 010 8h-1" />
     </svg>
     Back
  </a>

            <button id="shuffle" class="btn secondary">Shuffle Pool</button>
            <button id="reset" class="btn">Reset</button>
          </div>
          <div class="small-note">LCM target: <strong class="lcm-highlight"><?= $lcm ?></strong></div>
        </div>

        <div class="tiles-play-area">
          <div style="flex:1;min-width:260px">
            <div class="lane" id="laneA" data-num="<?= $a ?>">
              <div class="lane-header">Lane A (multiples of <?= $a ?>)</div>
            </div>
          </div>

          <div style="flex:1;min-width:260px">
            <div class="lane" id="laneB" data-num="<?= $b ?>">
              <div class="lane-header">Lane B (multiples of <?= $b ?>)</div>
            </div>
          </div>

          <div style="flex:1;min-width:260px">
            <div class="pool" id="pool" aria-label="Tiles pool">
              <?php foreach($pool as $val): ?>
                <div class="tile" tabindex="0" draggable="true" data-value="<?= $val ?>"><?= $val ?></div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <div id="status" style="margin-top:12px" class="small-note">Drag tiles from the pool into each lane. Try to match the same top number.</div>
      </div>
    </main>
  </div>

  <script>
  (function(){
    const pool = document.getElementById('pool');
    const laneA = document.getElementById('laneA');
    const laneB = document.getElementById('laneB');
    const status = document.getElementById('status');
    const resetBtn = document.getElementById('reset');
    const shuffleBtn = document.getElementById('shuffle');
    const target = <?= json_encode($lcm) ?>;

    function updateStatus(){
      const topA = laneA.lastElementChild ? parseInt(laneA.lastElementChild.dataset.value) : null;
      const topB = laneB.lastElementChild ? parseInt(laneB.lastElementChild.dataset.value) : null;
      if (topA && topB && topA === topB){
        laneA.classList.add('found'); laneB.classList.add('found');
        status.textContent = `🎉 Nice! Both lanes reach ${topA}. ${ topA === target ? 'That is the LCM!' : 'This is a common multiple.'}`;
      } else {
        laneA.classList.remove('found'); laneB.classList.remove('found');
        status.textContent = 'Keep building — try to get the same number on both lanes.';
      }
    }

    // drag logic
    let dragged = null;
    document.addEventListener('dragstart', e => {
      if (e.target.classList.contains('tile')){
        dragged = e.target; e.target.classList.add('dragging');
      }
    });
    document.addEventListener('dragend', e => { if (e.target.classList) e.target.classList.remove('dragging'); dragged = null; updateStatus(); });

    [laneA,laneB,pool].forEach(el=>{
      el.addEventListener('dragover', e => { e.preventDefault(); el.classList.add('drop-hint'); });
      el.addEventListener('dragleave', e=> el.classList.remove('drop-hint'));
      el.addEventListener('drop', e => {
        e.preventDefault(); el.classList.remove('drop-hint');
        if (!dragged) return;
        // clone from pool when dropping into lane from pool, otherwise move
        if (dragged.parentElement === pool){
          const clone = dragged.cloneNode(true);
          clone.addEventListener('dragstart', ev=>{ dragged = clone; clone.classList.add('dragging'); });
          clone.addEventListener('dragend', ev=>{ clone.classList.remove('dragging'); dragged=null; updateStatus(); });
          el.appendChild(clone);
        } else {
          el.appendChild(dragged);
        }
        updateStatus();
      });
    });

    // keyboard support: focus tile then press Enter to move it to the pool or lanes
    document.addEventListener('keydown', e => {
      if (e.key !== 'Enter') return;
      const t = document.activeElement;
      if (!t || !t.classList.contains('tile')) return;
      // toggle cycle: pool -> laneA -> laneB -> pool
      const parent = t.parentElement;
      if (parent.id === 'pool') laneA.appendChild(t);
      else if (parent.id === 'laneA') laneB.appendChild(t);
      else pool.appendChild(t);
      updateStatus();
    });

    resetBtn.addEventListener('click', ()=>{
      // move all tiles back to pool (keep original order)
      document.querySelectorAll('.lane .tile').forEach(t=> pool.appendChild(t));
      updateStatus();
    });

    shuffleBtn.addEventListener('click', ()=>{
      const tiles = Array.from(pool.querySelectorAll('.tile'));
      for(let i=tiles.length-1;i>0;i--){
        const j = Math.floor(Math.random()*(i+1));
        pool.insertBefore(tiles[j], tiles[i]);
      }
    });

    updateStatus();
  })();
  </script>
</body>
</html>
