<?php
function findLCM($a, $b) {
    $max = max($a, $b);
    $lcm = $max;

    while (true) {
        if ($lcm % $a == 0 && $lcm % $b == 0) {
            return $lcm;
        }
        $lcm++;
    }
}

$lcm = null;
if (isset($_POST['num1']) && isset($_POST['num2'])) {
    $num1 = (int)$_POST['num1'];
    $num2 = (int)$_POST['num2'];
    $lcm = findLCM($num1, $num2);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>LCM Tower Builder</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="container">
            <header class="app-header">
                <h1>🏰 LCM Tower Builder</h1>
                <p class="subtitle">Enter two numbers to visualize their multiples and find the LCM</p>
            </header>
            <main class="card" style="margin-top:18px;padding:20px">
                <div style="display:flex;gap:16px;align-items:center;justify-content:space-between">
                    <a href="activity.php" class="cta-hero" title="Try the interactive LCM activity">
                        <span class="emoji">🧩</span>
                        <div>
                            <div>Try the LCM Activity</div>
                            <small>Play & find the common multiple</small>
                        </div>
                    </a>
                    <a href="tiles.php" class="cta-hero" title="Try the interactive LCM activity">
                        <span class="emoji">🧩</span>
                        <div>
                            <div>Try the LCM Activity</div>
                            <small>Play & form the tower</small>
                        </div>
                    </a>
                    <div style="margin-left:16px;min-width:260px">
                        <div class="info-card">
                            <h3>What is LCM?</h3>
                            <p>The Least Common Multiple (LCM) of two integers is the smallest positive integer that is a multiple of both numbers.</p>
                            <div class="formula-box" style="margin-top:8px">LCM(a, b) = (a × b) / GCD(a, b)</div>
                            <p style="margin-top:8px;font-weight:700;color:var(--muted)">Quick tip: list a few multiples of each number and look for the smallest match.</p>
                        </div>
                    </div>
                </div>
                <form method="post" class="input-section" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                    <label for="num1">Number 1</label>
                    <input id="num1" type="number" name="num1" required min="1" value="<?= isset($_POST['num1']) ? (int)$_POST['num1'] : 4 ?>">

                    <label for="num2">Number 2</label>
                    <input id="num2" type="number" name="num2" required min="1" value="<?= isset($_POST['num2']) ? (int)$_POST['num2'] : 6 ?>">

                    <button class="btn" type="submit">Build Towers</button>
                </form>

                <?php if ($lcm):
                    $a = (int)
$_POST['num1'];
                    $b = (int)
$_POST['num2'];
                ?>
                <section style="margin-top:18px">
                    <div class="towers-container">
                        <div class="tower" id="tower1">
                            <div class="tower-label">Number 1: <span id="num1-display"><?= htmlspecialchars($a) ?></span></div>
                            <?php for ($i = 1; $i <= $lcm / $a; $i++):
                                $val = $a * $i;?>
                                <div class="block block-1" data-value="<?= $val ?>"><?= $val ?></div>
                            <?php endfor; ?>
                        </div>

                        <div class="tower" id="tower2">
                            <div class="tower-label">Number 2: <span id="num2-display"><?= htmlspecialchars($b) ?></span></div>
                            <?php for ($i = 1; $i <= $lcm / $b; $i++):
                                $val = $b * $i;?>
                                <div class="block block-2" data-value="<?= $val ?>"><?= $val ?></div>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div style="margin-top:12px " class="formula-box">🎉 The LCM of <?= htmlspecialchars($a) ?> and <?= htmlspecialchars($b) ?> is <span class="lcm-highlight"><?= $lcm ?></span></div>
                    <div style="margin-top:12px">
                    </div>
                </section>
                <?php endif; ?>

            </main>
        </div>

        <script src="script.js"></script>
    </body>
    </html>
