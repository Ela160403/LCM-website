<?php
function findLCM($a, $b) {
        $max = max($a, $b);
        $lcm = $max;
        while (true) {
                if ($lcm % $a == 0 && $lcm % $b == 0) return $lcm;
                $lcm++;
        }
}

$num1 = rand(2, 6);
$num2 = rand(2, 8);
$lcm = findLCM($num1, $num2);
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>LCM Tower Activity</title>
        <link rel="stylesheet" href="style.css">
        <style>
            @keyframes shake { 0%,100%{transform:translateX(0)}25%{transform:translateX(-6px)}50%{transform:translateX(6px)}75%{transform:translateX(-6px)}}
            .shake{animation:shake .35s}
        </style>
</head>
<body style="padding:28px;">
    <div class="container">
<header style="display: flex; align-items: center; justify-content: space-between;
               background: #fff; padding: 8px 14px; border-radius: 12px 12px 0 0;
               box-shadow: 0 2px 4px rgba(0,0,0,0.08);">

  <h1 style="margin: 0; font-size: 1.1rem; font-weight: 700; color: #1f2937;">
    🎮 LCM Tower Activity
  </h1>

</header>

        <main class="card" style="margin-top:18px;padding:20px">
            <h2 style="margin:0 0 8px 0">LCM Pie Challenge</h2>
            <p style="font-weight:600;color:var(--muted)">Enter multiples into each pie. Each correct multiple fills a slice. When both pies are full, the LCM pops!</p>
            
                <div style="display:flex;flex-direction:column;align-items:left;gap:8px">
                    <div id="statusBadge" class="success-badge" style="display:none">Waiting...</div>
                    <div style="margin-top:6px;display:flex;gap:8px">
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

                        <button id="newNumbers" class="btn" title="Pick new numbers">🔁 New Numbers</button>
                        <button id="hardMode" class="btn" title="Hard mode: bigger numbers">🔥 Hard</button>
                        <button id="applyGcd" class="btn" title="Divide both numbers by their GCD" style="display:none">➗ Add GCD</button>
                        <button id="showAnswerBtn" class="btn secondary">Show Answer</button>
                    </div>
                </div>


            <div class="pie-area">
                <div class="pie-wrap">
                    <div class="pie" id="pieA" style="--pie-color:#ff7a7a">
                        <div class="fill" id="fillA" style="--fill:0"></div>
                        <div class="mask"></div>
                        <div class="label" id="labelA">0%</div>
                    </div>
                    <div>Multiples of <strong id="baseA"><?= $num1 ?></strong></div>
                    <div class="input-row">
                        <input id="inputA" type="number" placeholder="Enter multiple" aria-label="Enter multiple for A">
                        <button id="btnA" class="btn">OK</button>
                    </div>
                </div>
       <div class="small-note">Goal: Fill both pies.</div>
             
                <div class="pie-wrap">
                    <div class="pie pie-small" id="pieB" style="--pie-color:#2dd4bf">
                        <div class="fill" id="fillB" style="--fill:0"></div>
                        <div class="mask"></div>
                        <div class="label" id="labelB">0%</div>
                    </div>
                    <div>Multiples of <strong id="baseB"><?= $num2 ?></strong></div>
                    <div class="input-row">
                        <input id="inputB" type="number" placeholder="Enter multiple" aria-label="Enter multiple for B">
                        <button id="btnB" class="btn">OK</button>
                    </div>
                </div>
            </div>

            <!-- Show answer area (reveals formula steps) -->
            <div id="answerDrop" style="display:none;margin-top:12px;padding:10px;border-radius:10px;background:#fff;border:1px solid #eef2ff">
                <div id="answerSteps"></div>
            </div>
            <!-- hidden solver fields used by Show Answer -->
            <div style="display:none">
                <span id="solverA"><?= $num1 ?></span>
                <span id="solverB"><?= $num2 ?></span>
                <span id="solverG">?</span>
            </div>

            <div id="message" aria-live="polite" class="message-big" style="margin-top:12px;padding:10px;background:#fff;border-radius:10px;">
                
            Fill each pie by entering multiples. Try entering <?= $num1 ?>, <?= $num2 ?>, then multiples like <?= $num1*2 ?>, <?= $num2*2 ?>, etc.</div>
        </main>
    </div>

    <script>
    (function(){
        // initial server-provided numbers (used only for first load)
        let numA = <?= json_encode($num1) ?>;
        let numB = <?= json_encode($num2) ?>;

        // DOM refs
        const pieA = document.getElementById('pieA');
        const pieB = document.getElementById('pieB');
        const fillAEl = document.getElementById('fillA');
        const fillBEl = document.getElementById('fillB');
        const labelA = document.getElementById('labelA');
        const labelB = document.getElementById('labelB');
        const inputA = document.getElementById('inputA');
        const inputB = document.getElementById('inputB');
        const btnA = document.getElementById('btnA');
        const btnB = document.getElementById('btnB');
        const message = document.getElementById('message');
        const statusBadge = document.getElementById('statusBadge');
    const newBtn = document.getElementById('newNumbers');
    const hardBtn = document.getElementById('hardMode');
    const applyGcdBtn = document.getElementById('applyGcd');
        const baseAEl = document.getElementById('baseA');
        const baseBEl = document.getElementById('baseB');

        // runtime state
        let multiplesA = [];
        let multiplesB = [];
        let filledA = 0, filledB = 0;
        let targetLCM = 1;

        function gcd(a,b){ while(b){ [a,b]=[b,a%b]; } return a; }
        function lcm(a,b){ return (a*b)/gcd(a,b); }

        function computeMultiples(base, limit){ const arr=[]; for(let m=base; m<=limit; m+=base) arr.push(m); return arr; }

        function updatePieByCount(pieEl, fillEl, labelEl, count, total){
            const percent = Math.min(100, Math.round((count / total) * 100));
            labelEl.textContent = percent + '%';
            fillEl.style.setProperty('--fill', percent);
            if (percent === 100) pieEl.classList.add('pie-joy'); else pieEl.classList.remove('pie-joy');
        }

        function celebrateLCM(){
            message.textContent = `🎉 Hooray! LCM is ${targetLCM}!`;
            statusBadge.style.display='block'; statusBadge.textContent='You did it!';
            const el = document.querySelector('.message-big'); el.classList.add('confetti'); setTimeout(()=>el.classList.remove('confetti'),1600);
        }

        function resetFor(num1, num2){
            numA = num1; numB = num2;
            targetLCM = lcm(numA, numB);
            multiplesA = computeMultiples(numA, targetLCM);
            multiplesB = computeMultiples(numB, targetLCM);
            filledA = 0; filledB = 0;
            baseAEl.textContent = numA; baseBEl.textContent = numB;
            statusBadge.style.display='none'; statusBadge.textContent='';
            message.textContent = `Fill each pie by entering multiples. Try entering ${numA}, ${numB}, then ${numA*2}, ${numB*2}...`;
            updatePieByCount(pieA, fillAEl, labelA, 0, multiplesA.length);
            updatePieByCount(pieB, fillBEl, labelB, 0, multiplesB.length);
        }

        function tryNextMultiple(value, multiplesArr, filledCount, pieEl, fillEl, labelEl, base){
            const v = Number(value);
            if (!v || v <= 0){ message.textContent = 'Please enter a number.'; return filledCount; }
            const expected = multiplesArr[filledCount];
            if (v === expected){
                filledCount++;
                updatePieByCount(pieEl, fillEl, labelEl, filledCount, multiplesArr.length);
                if (filledCount === multiplesArr.length) message.textContent = `Nice! You completed multiples for ${base}.`; else message.textContent = 'Great! Keep going.';
            } else {
                message.textContent = `Try the next multiple: ${expected} (it's ${base} × ${filledCount+1}).`;
            }
            return filledCount;
        }

        btnA.addEventListener('click', ()=>{
            filledA = tryNextMultiple(inputA.value, multiplesA, filledA, pieA, fillAEl, labelA, numA);
            inputA.value = '';
            if (filledA === multiplesA.length && filledB === multiplesB.length) celebrateLCM();
        });
        btnB.addEventListener('click', ()=>{
            filledB = tryNextMultiple(inputB.value, multiplesB, filledB, pieB, fillBEl, labelB, numB);
            inputB.value = '';
            if (filledA === multiplesA.length && filledB === multiplesB.length) celebrateLCM();
        });

        inputA.addEventListener('keydown', e => { if (e.key === 'Enter') btnA.click(); });
        inputB.addEventListener('keydown', e => { if (e.key === 'Enter') btnB.click(); });

        newBtn.addEventListener('click', ()=>{
            // pick new numbers (similar ranges as server): keep kid-friendly small numbers
            const a = Math.floor(Math.random() * 5) + 2; // 2..6
            const b = Math.floor(Math.random() * 7) + 2; // 2..8
            resetFor(a,b);
            applyGcdBtn.style.display = 'none';
            // update solver (so Show Answer reflects these numbers)
            document.getElementById('solverA') && (document.getElementById('solverA').textContent = a);
            document.getElementById('solverB') && (document.getElementById('solverB').textContent = b);
            document.getElementById('solverG') && (document.getElementById('solverG').textContent = '?');
            // hide prior answer
            document.getElementById('answerDrop') && (document.getElementById('answerDrop').style.display = 'none');
        });

        hardBtn.addEventListener('click', ()=>{
            // Hard mode: double-digit bases (10..30)
            const a = Math.floor(Math.random() * 21) + 10; // 10..30
            const b = Math.floor(Math.random() * 21) + 10; // 10..30
            resetFor(a,b);
            // show the Add GCD button in hard mode (it will check gcd > 1 when clicked)
            applyGcdBtn.style.display = 'inline-block';
            // update solver fields for Show Answer
            document.getElementById('solverA') && (document.getElementById('solverA').textContent = a);
            document.getElementById('solverB') && (document.getElementById('solverB').textContent = b);
            document.getElementById('solverG') && (document.getElementById('solverG').textContent = '?');
            document.getElementById('answerDrop') && (document.getElementById('answerDrop').style.display = 'none');
        });

        // Simplified: applyGcd directly when clicked (hard mode shows the button)
        applyGcdBtn.addEventListener('click', ()=>{
            const g = gcd(numA, numB);
            if (g <= 1){ message.textContent = 'GCD is 1 (numbers are co-prime). Cannot apply division.'; return; }
            const origA = numA, origB = numB;
            const a = Math.floor(numA / g), b = Math.floor(numB / g);
            message.textContent = `GCD ${g} applied: ${origA}→${a}, ${origB}→${b}. Enter multiples of the divided values.`;
            applyGcdBtn.style.display = 'none';
            // set solver fields for Show Answer
            document.getElementById('solverA') && (document.getElementById('solverA').textContent = origA);
            document.getElementById('solverB') && (document.getElementById('solverB').textContent = origB);
            document.getElementById('solverG') && (document.getElementById('solverG').textContent = g);
            resetFor(a, b);
        });

        // Show Answer button reveals formula steps for the most recent original bases
        const showAnswerBtn = document.getElementById('showAnswerBtn');
        const answerDrop = document.getElementById('answerDrop');
        const answerSteps = document.getElementById('answerSteps');
        showAnswerBtn.addEventListener('click', () => {
    // toggle visibility
    if (answerDrop.style.display === 'block') {
        // currently shown → hide it
        answerDrop.style.display = 'none';
        showAnswerBtn.textContent = 'Show Answer ▼'; // optional: change button text
    } else {
        // currently hidden → show it
        const origA = Number(document.getElementById('solverA')?.textContent) || numA;
        const origB = Number(document.getElementById('solverB')?.textContent) || numB;
        const g = gcd(origA, origB);
        const trueL = (origA * origB) / g;
        answerSteps.innerHTML = `Formula: LCM = (a × b) ÷ G<br>Steps:<br>1) a × b = ${origA} × ${origB} = ${origA*origB}<br>2) G = ${g}<br>3) LCM = ${origA*origB} ÷ ${g} = <strong>${trueL}</strong>`;
        answerDrop.style.display = 'block';
        showAnswerBtn.textContent = 'Hide Answer ▲'; // optional: change button text
    }
});


        // initialize from server-provided values
        resetFor(numA, numB);

    })();
    </script>
</body>
</html>
