/* DD TEST V2 – VIACKROKOVÝ TEST, JEDNA OTÁZKA NA OBRAZOVKE */

if (!function_exists('ddx_v2_shortcode')) {

    function ddx_v2_shortcode() {

        $questions = array(
            1 => 'Kontrolujem si správy/aktivity na mobile hneď po zobudení?',
            2 => 'Telefón mám stále po ruke, mám ho aj pri posteli.',
            3 => 'Cítim nutkanie skontrolovať si mobil aj bez toho, aby mi prišla notifikácia.',
            4 => 'Odkladám úlohy a povinnosti na úkor času pred obrazovkou.',
            5 => 'Nedokážem sa sústrediť pri práci/štúdiu kvôli online vyrušeniam.',
            6 => 'Chodievam spať neskôr, pretože som dlhšie za počítačom alebo na mobile.',
            7 => 'Mobil používam aj pri prechádzke alebo jazde autom.',
            8 => 'Jedávam s mobilom v ruke alebo pred televízorom, či notebookom.',
            9 => 'Keď som bez mobilu, bývam nervózny.',
            10 => 'Pred okolím ukrývam moje aktivity na mobile.',
            11 => 'Denne trávim na sociálnych sieťach viac času, než by som chcel.',
            12 => 'Cítim sa zle, keď mi niečo unikne (novinky/správy).',
            13 => 'Myslím si, že trávim na mobile viac času, než je pre mňa zdravé.'
        );

        $frequency_questions = array(1, 2, 3, 4, 5, 6, 9, 12);

        $frequency_answers = array(
            'nikdy',
            'zriedkavo',
            'často',
            'veľmi často'
        );

        $yes_no_answers = array('áno', 'nie');

        $result = false;

        if (isset($_GET['dd_result'])) {
            $token = sanitize_text_field(wp_unslash($_GET['dd_result']));
            $result = get_transient('ddx_v2_result_' . $token);
        }

        ob_start();

        $uid = wp_unique_id('ddx-v2-');
        ?>

        <div class="ddx-v2" id="<?php echo esc_attr($uid); ?>">

        <?php if (is_array($result) && isset($result['score'], $result['category'])) : ?>

            <?php
            $score = (int) $result['score'];
            $category = sanitize_text_field($result['category']);

            if ($score <= 9) {
                $heading = 'Máte digitálny svet prevažne pod kontrolou';
                $description = 'Skvelé, máte dobré digitálne návyky a váš vzťah k technológiám je celkom zdravý.';
                $detail = 'Vaše odpovede naznačujú, že digitálne technológie pravdepodobne dokážete využívať bez výraznejšieho narúšania každodenného života. Udržiavajte si zdravé návyky a nezabúdajte na čas offline.';
                $url = 'https://www.digitalnydetox.sk/obchod/detailny-digitalny-profil-nizke-riziko/';
                $cta = 'Získať detailný digitálny profil';
            } elseif ($score <= 20) {
                $heading = 'Technológie začínajú ovplyvňovať váš každodenný život';
                $description = 'Vaše odpovede naznačujú, že digitálne návyky môžu ovplyvňovať vašu pozornosť, oddych alebo vzťahy.';
                $detail = 'Niektoré vaše digitálne návyky si zaslúžia pozornosť. Možno by vám prospelo lepšie nastavenie hraníc pri používaní mobilu a viac priestoru na aktivity mimo obrazoviek.';
                $url = 'https://www.digitalnydetox.sk/obchod/detailny-digitalny-profil-stredne-riziko/';
                $cta = 'Získať detailný digitálny profil';
            } else {
                $heading = 'Váš digitálny život výrazne zasahuje do offline sveta';
                $description = 'Vaše odpovede poukazujú na viacero výrazných signálov digitálnej záťaže.';
                $detail = 'Používanie digitálnych technológií môže významnejšie zasahovať do vášho oddychu, sústredenia alebo každodenných návykov. Užitočným prvým krokom môže byť podrobnejšie pochopenie vlastného digitálneho správania a postupná zmena návykov.';
                $url = 'https://www.digitalnydetox.sk/obchod/detailny-digitalny-profil-vysoke-riziko/';
                $cta = 'Získať detailný digitálny profil';
            }
            ?>

            <div class="ddx-result">
                <div class="ddx-result-mark">✓</div>
                <div class="ddx-eyebrow">VÝSLEDOK TESTU</div>
                <h2><?php echo esc_html($heading); ?></h2>
                <div class="ddx-score">
                    <strong><?php echo esc_html($score); ?></strong>
                    <span>z 32 bodov</span>
                </div>
                <div class="ddx-category"><?php echo esc_html($category); ?></div>
                <p class="ddx-result-lead"><?php echo esc_html($description); ?></p>
                <p><?php echo esc_html($detail); ?></p>
                <p class="ddx-disclaimer">
                    Výsledok je orientačný a slúži na lepšie pochopenie digitálnych návykov.
                    Nejde o odbornú ani zdravotnú diagnostiku.
                </p>
                <a class="ddx-primary" href="<?php echo esc_url($url); ?>">
                    <?php echo esc_html($cta); ?>
                </a>
                <a class="ddx-restart" href="<?php echo esc_url(remove_query_arg('dd_result')); ?>">
                    Vyplniť test znova
                </a>
            </div>

        <?php else : ?>

            <form class="ddx-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">

                <input type="hidden" name="action" value="ddx_v2_submit">
                <input type="hidden" name="ddx_return" value="<?php echo esc_url(get_permalink()); ?>">

                <?php
                wp_nonce_field('ddx_v2_submit', 'ddx_nonce');
                ?>

                <?php foreach ($questions as $number => $question) : ?>
                    <input type="hidden" name="q<?php echo (int) $number; ?>" value="">
                <?php endforeach; ?>

                <!-- ÚVODNÁ OBRAZOVKA -->

                <section class="ddx-screen ddx-intro">
                    <div class="ddx-eyebrow">DIGITÁLNY DETOX</div>
                    <h2>Prerastajú vám obrazovky cez hlavu?</h2>
                    <h3>Test vzťahu k digitálnym technológiám</h3>
                    <p>
                        Test vám pomôže zistiť, ako ste na tom, pokiaľ ide o váš
                        vzťah k obrazovkám. Slúži na orientačné zhodnotenie
                        digitálnych návykov a času, ktorý venujete digitálnym technológiám.
                    </p>
                    <div class="ddx-info">
                        <span class="ddx-info-icon">i</span>
                        <span>
                            Nejde o odbornú ani zdravotnú diagnostiku.
                            Výsledok testu je orientačný a nenahrádza odborné posúdenie.
                        </span>
                    </div>
                    <div class="ddx-meta">13 otázok · približne 3 minúty</div>
                    <button type="button" class="ddx-primary ddx-start">
                        Spustiť test <span>→</span>
                    </button>
                </section>

                <!-- OTÁZKY -->

                <section class="ddx-screen ddx-question-screen" hidden>

                    <div class="ddx-progress-area">
                        <div class="ddx-progress-label">
                            <span class="ddx-question-number">Otázka 1 z 13</span>
                            <span class="ddx-progress-percent">8 %</span>
                        </div>
                        <div class="ddx-progress-track">
                            <div class="ddx-progress-fill"></div>
                        </div>
                    </div>

                    <div class="ddx-question-content">
                        <div class="ddx-eyebrow">DIGITÁLNE NÁVYKY</div>
                        <h2 class="ddx-question-title"></h2>
                        <p class="ddx-question-hint">Vyberte odpoveď, ktorá vás najviac vystihuje.</p>
                        <div class="ddx-options"></div>
                    </div>

                    <div class="ddx-navigation">
                        <button type="button" class="ddx-back">← Späť</button>
                    </div>

                </section>

                <!-- E-MAIL A PODMIENKY -->

                <section class="ddx-screen ddx-contact-screen" hidden>

                    <div class="ddx-eyebrow">POSLEDNÝ KROK</div>
                    <h2>Váš výsledok je pripravený</h2>
                    <p>
                        Zadajte e-mailovú adresu a zobrazíme vám vyhodnotenie testu.
                    </p>

                    <label class="ddx-label" for="<?php echo esc_attr($uid); ?>-email">
                        E-mailová adresa
                    </label>
                    <input
                        id="<?php echo esc_attr($uid); ?>-email"
                        class="ddx-email"
                        type="email"
                        name="ddx_email"
                        placeholder="vas@email.sk"
                        required
                        autocomplete="email"
                    >

                    <div class="ddx-terms-box">
                        <details>
                            <summary>Podmienky používania</summary>
                            <p>
                                Tento test je bezplatný a slúži výlučne na informatívne
                                a orientačné účely. Vyplnením a odoslaním testu nevzniká
                                návštevníkovi žiadna povinnosť úhrady ani záväzok k nákupu.
                            </p>
                            <p>
                                Na základe výsledku testu môže byť návštevníkovi dobrovoľne
                                ponúknutá možnosť presmerovania na webovú stránku
                                www.digitalnydetox.sk, kde je možné zakúpiť si detailnejší
                                alebo rozšírený výsledok testu alebo súvisiace služby.
                                Nákup prebieha výhradne na tejto webovej stránke a riadi sa
                                jej samostatnými obchodnými podmienkami.
                            </p>
                            <p>
                                Údaje uvedené v teste sú spracované v súlade s platnými
                                právnymi predpismi o ochrane osobných údajov a výlučne
                                za účelom vyhodnotenia testu a zobrazenia výsledku.
                                Prevádzkovateľ testu nezodpovedá za rozhodnutia návštevníka
                                prijaté na základe orientačného výsledku testu.
                            </p>
                            <p>
                                Používaním tohto testu potvrdzujete, že ste sa s týmito
                                podmienkami oboznámili a súhlasíte s nimi.
                            </p>
                        </details>

                        <label class="ddx-checkbox-label">
                            <input type="checkbox" name="ddx_terms" value="1" required>
                            <span>Oboznámil/a som sa s podmienkami používania a súhlasím s nimi.</span>
                        </label>
                    </div>

                    <div class="ddx-navigation ddx-contact-navigation">
                        <button type="button" class="ddx-back">← Späť na otázky</button>
                        <button type="submit" class="ddx-primary ddx-submit">
                            Vyhodnotiť test <span>→</span>
                        </button>
                    </div>

                </section>

            </form>

        <?php endif; ?>

        </div>

        <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

        #<?php echo esc_attr($uid); ?> {
            font-family: 'Poppins', sans-serif;
            color: #171717;
            max-width: 760px;
            margin: 35px auto;
            font-size: 15px;
            line-height: 1.7;
        }

        #<?php echo esc_attr($uid); ?> *,
        #<?php echo esc_attr($uid); ?> *::before,
        #<?php echo esc_attr($uid); ?> *::after {
            box-sizing: border-box;
        }

        #<?php echo esc_attr($uid); ?> .ddx-screen,
        #<?php echo esc_attr($uid); ?> .ddx-result {
            background: #fff;
            border: 1px solid #e9e9e9;
            border-radius: 12px;
            padding: 42px;
            box-shadow: 0 8px 35px rgba(0,0,0,.045);
        }

        #<?php echo esc_attr($uid); ?> [hidden] {
            display: none !important;
        }

        #<?php echo esc_attr($uid); ?> .ddx-eyebrow {
            color: #8b7500;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            margin-bottom: 13px;
        }

        #<?php echo esc_attr($uid); ?> h2 {
            color: #171717;
            font-family: 'Poppins', sans-serif;
            font-size: 27px;
            line-height: 1.35;
            font-weight: 600;
            margin: 0 0 17px;
        }

        #<?php echo esc_attr($uid); ?> h3 {
            font-family: 'Poppins', sans-serif;
            font-size: 17px;
            font-weight: 500;
            margin: 0 0 15px;
        }

        #<?php echo esc_attr($uid); ?> p {
            margin: 0 0 16px;
        }

        #<?php echo esc_attr($uid); ?> .ddx-info {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            background: #fff9e5;
            border-radius: 8px;
            padding: 15px;
            margin: 23px 0;
            font-size: 13px;
        }

        #<?php echo esc_attr($uid); ?> .ddx-info-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 21px;
            height: 21px;
            border-radius: 50%;
            background: #FEBF00;
            font-weight: 700;
            font-size: 13px;
        }

        #<?php echo esc_attr($uid); ?> .ddx-meta {
            color: #777;
            font-size: 12px;
            margin: 22px 0;
        }

        #<?php echo esc_attr($uid); ?> .ddx-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            min-height: 51px;
            padding: 13px 27px;
            background: #FEBF00;
            border: 1px solid #FEBF00;
            border-radius: 8px;
            color: #171717;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: background .2s ease, transform .2s ease;
        }

        #<?php echo esc_attr($uid); ?> .ddx-primary:hover {
            background: #eeb300;
            border-color: #eeb300;
            color: #171717;
            transform: translateY(-1px);
        }

        #<?php echo esc_attr($uid); ?> button:focus-visible,
        #<?php echo esc_attr($uid); ?> input:focus-visible,
        #<?php echo esc_attr($uid); ?> a:focus-visible {
            outline: 3px solid rgba(254,191,0,.45);
            outline-offset: 3px;
        }

        #<?php echo esc_attr($uid); ?> .ddx-progress-area {
            margin-bottom: 38px;
        }

        #<?php echo esc_attr($uid); ?> .ddx-progress-label {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        #<?php echo esc_attr($uid); ?> .ddx-progress-percent {
            color: #777;
        }

        #<?php echo esc_attr($uid); ?> .ddx-progress-track {
            height: 6px;
            background: #ededed;
            border-radius: 10px;
            overflow: hidden;
        }

        #<?php echo esc_attr($uid); ?> .ddx-progress-fill {
            width: 7.69%;
            height: 100%;
            background: #FEBF00;
            border-radius: 10px;
            transition: width .3s ease;
        }

        #<?php echo esc_attr($uid); ?> .ddx-question-content {
            animation: ddxFadeIn .3s ease;
        }

        #<?php echo esc_attr($uid); ?> .ddx-question-title {
            min-height: 75px;
            font-size: 23px;
            margin-bottom: 12px;
        }

        #<?php echo esc_attr($uid); ?> .ddx-question-hint {
            color: #777;
            font-size: 13px;
            margin-bottom: 24px;
        }

        #<?php echo esc_attr($uid); ?> .ddx-options {
            display: flex;
            flex-direction: column;
            gap: 11px;
        }

        #<?php echo esc_attr($uid); ?> .ddx-option {
            display: flex;
            align-items: center;
            width: 100%;
            min-height: 57px;
            padding: 14px 18px;
            border: 1px solid #dedede;
            border-radius: 8px;
            background: #fff;
            color: #171717;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            text-align: left;
            cursor: pointer;
            transition: border-color .18s ease, background .18s ease, transform .18s ease;
        }

        #<?php echo esc_attr($uid); ?> .ddx-option:hover {
            border-color: #d5a000;
            background: #fffdf5;
            transform: translateX(2px);
        }

        #<?php echo esc_attr($uid); ?> .ddx-option:active {
            background: #fff5d2;
        }

        #<?php echo esc_attr($uid); ?> .ddx-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-top: 30px;
        }

        #<?php echo esc_attr($uid); ?> .ddx-back {
            background: transparent;
            border: 0;
            padding: 10px 0;
            color: #555;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            cursor: pointer;
        }

        #<?php echo esc_attr($uid); ?> .ddx-back:hover {
            color: #000;
            text-decoration: underline;
        }

        #<?php echo esc_attr($uid); ?> .ddx-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin: 24px 0 8px;
        }

        #<?php echo esc_attr($uid); ?> .ddx-email {
            display: block;
            width: 100%;
            height: 52px;
            padding: 12px 15px;
            border: 1px solid #dedede;
            border-radius: 8px;
            background: #fff;
            color: #171717;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
        }

        #<?php echo esc_attr($uid); ?> .ddx-terms-box {
            margin-top: 25px;
            padding: 17px;
            background: #fafafa;
            border: 1px solid #eee;
            border-radius: 8px;
        }

        #<?php echo esc_attr($uid); ?> .ddx-terms-box details summary {
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        #<?php echo esc_attr($uid); ?> .ddx-terms-box details p {
            font-size: 12px;
            line-height: 1.7;
            margin: 12px 0;
        }

        #<?php echo esc_attr($uid); ?> .ddx-checkbox-label {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-top: 17px;
            font-size: 12px;
            line-height: 1.6;
            cursor: pointer;
        }

        #<?php echo esc_attr($uid); ?> .ddx-checkbox-label input {
            flex: 0 0 auto;
            width: 17px;
            height: 17px;
            margin-top: 2px;
            accent-color: #dba500;
        }

        #<?php echo esc_attr($uid); ?> .ddx-contact-navigation {
            margin-top: 28px;
        }

        #<?php echo esc_attr($uid); ?> .ddx-submit {
            border: 0;
        }

        #<?php echo esc_attr($uid); ?> .ddx-result {
            text-align: center;
        }

        #<?php echo esc_attr($uid); ?> .ddx-result-mark {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 22px;
            border-radius: 50%;
            background: #fff3c4;
            font-size: 24px;
            font-weight: 600;
        }

        #<?php echo esc_attr($uid); ?> .ddx-score {
            margin: 24px 0 8px;
        }

        #<?php echo esc_attr($uid); ?> .ddx-score strong {
            display: block;
            font-size: 48px;
            line-height: 1.2;
            font-weight: 600;
        }

        #<?php echo esc_attr($uid); ?> .ddx-score span {
            color: #777;
            font-size: 12px;
        }

        #<?php echo esc_attr($uid); ?> .ddx-category {
            display: inline-block;
            padding: 7px 17px;
            margin: 12px 0 23px;
            background: #fff3c4;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        #<?php echo esc_attr($uid); ?> .ddx-result-lead {
            font-size: 16px;
            font-weight: 500;
        }

        #<?php echo esc_attr($uid); ?> .ddx-disclaimer {
            color: #777;
            font-size: 11px;
            margin: 25px 0;
        }

        #<?php echo esc_attr($uid); ?> .ddx-restart {
            display: block;
            margin-top: 20px;
            color: #555;
            font-size: 12px;
            text-decoration: underline;
        }

        @keyframes ddxFadeIn {
            from { opacity: 0; transform: translateY(7px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 600px) {
            #<?php echo esc_attr($uid); ?> {
                margin: 15px auto;
            }

            #<?php echo esc_attr($uid); ?> .ddx-screen,
            #<?php echo esc_attr($uid); ?> .ddx-result {
                padding: 25px 20px;
                border-radius: 9px;
            }

            #<?php echo esc_attr($uid); ?> h2 {
                font-size: 23px;
            }

            #<?php echo esc_attr($uid); ?> .ddx-question-title {
                min-height: 0;
                font-size: 20px;
            }

            #<?php echo esc_attr($uid); ?> .ddx-contact-navigation {
                align-items: flex-start;
                flex-direction: column;
            }

            #<?php echo esc_attr($uid); ?> .ddx-contact-navigation .ddx-primary {
                width: 100%;
            }
        }
        </style>

        <script>
        (function () {

            const root = document.getElementById(<?php echo wp_json_encode($uid); ?>);
            if (!root || root.dataset.initialized === '1') return;
            root.dataset.initialized = '1';

            const form = root.querySelector('.ddx-form');
            if (!form) return;

            const intro = root.querySelector('.ddx-intro');
            const questionScreen = root.querySelector('.ddx-question-screen');
            const contactScreen = root.querySelector('.ddx-contact-screen');

            const title = root.querySelector('.ddx-question-title');
            const optionsBox = root.querySelector('.ddx-options');
            const numberLabel = root.querySelector('.ddx-question-number');
            const percentLabel = root.querySelector('.ddx-progress-percent');
            const progressFill = root.querySelector('.ddx-progress-fill');

            const questions = <?php echo wp_json_encode($questions); ?>;
            const frequencyQuestions = <?php echo wp_json_encode($frequency_questions); ?>;
            const frequencyAnswers = <?php echo wp_json_encode($frequency_answers); ?>;
            const yesNoAnswers = <?php echo wp_json_encode($yes_no_answers); ?>;

            let current = 1;
            let changing = false;

            function showScreen(screen) {
                intro.hidden = screen !== 'intro';
                questionScreen.hidden = screen !== 'question';
                contactScreen.hidden = screen !== 'contact';
            }

            function renderQuestion() {

                changing = false;

                const total = Object.keys(questions).length;
                const percent = Math.round((current / total) * 100);

                numberLabel.textContent = 'Otázka ' + current + ' z ' + total;
                percentLabel.textContent = percent + ' %';
                progressFill.style.width = percent + '%';

                title.textContent = questions[current];
                optionsBox.innerHTML = '';

                const answers = frequencyQuestions.includes(current)
                    ? frequencyAnswers
                    : yesNoAnswers;

                answers.forEach(function (answer) {

                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'ddx-option';
                    button.textContent = answer;

                    button.addEventListener('click', function () {

                        if (changing) return;
                        changing = true;

                        const hidden = form.querySelector('[name="q' + current + '"]');
                        hidden.value = answer;

                        button.style.borderColor = '#d5a000';
                        button.style.background = '#fff5d2';

                        window.setTimeout(function () {

                            if (current < total) {
                                current++;
                                renderQuestion();
                            } else {
                                showScreen('contact');
                            }

                        }, 220);
                    });

                    optionsBox.appendChild(button);
                });

                const backButton = questionScreen.querySelector('.ddx-back');
                backButton.style.visibility = current === 1 ? 'hidden' : 'visible';
            }

            root.querySelector('.ddx-start').addEventListener('click', function () {
                current = 1;
                renderQuestion();
                showScreen('question');
            });

            questionScreen.querySelector('.ddx-back').addEventListener('click', function () {
                if (current > 1) {
                    current--;
                    renderQuestion();
                } else {
                    showScreen('intro');
                }
            });

            contactScreen.querySelector('.ddx-back').addEventListener('click', function () {
                current = 13;
                renderQuestion();
                showScreen('question');
            });

            form.addEventListener('submit', function (event) {

                for (let i = 1; i <= 13; i++) {
                    const field = form.querySelector('[name="q' + i + '"]');
                    if (!field || !field.value) {
                        event.preventDefault();
                        current = i;
                        renderQuestion();
                        showScreen('question');
                        return;
                    }
                }

            });

            showScreen('intro');

        })();
        </script>

        <?php

        return ob_get_clean();
    }

    add_shortcode('dd_test', 'ddx_v2_shortcode');
}