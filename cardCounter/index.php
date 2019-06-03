<?php
// SETUP VARS
$suitArray = array("C", "S", "H", "D");
$strat = 'Hi-Lo';
$cardNameString = '';
for ($suits=1; $suits<=4; $suits++) {
    $currentSuit = $suitArray[$suits-1];
    for($cards=1; $cards<=13; $cards++) {
        $cardNameArray[] = $cards . '-' . $currentSuit; 
    }
}
shuffle($cardNameArray);
$cardNameString = implode(",", $cardNameArray);
?>
<html>
<head>
<script type="text/javascript" src="/js/jquery331.js"></script>

<style type="text/css">
    body { font-size: 18px; background-color: black; color: white; }
    input { font-size: 18px; width: 39px; background-color: #902424; border: blue; color: white; }
    #container { width: 1800px; }
    #card { background-size: contain; background-image: url("images/00.png"); width: <?= round(691*0.35) ?>px; height: <?= round(1056*0.35) ?>px; float: left; }
    #outputContainer, #strategy, #count, #offset, #showCardsCount, #go, #selectedSpeed {  float: left; }
    #strategy, #count, #offset, #showCardsCount, #go, #selectedSpeed { margin: 5px 15px; padding: 5px 15px; width: 200px; text-align: center; background-color: #902424; border: blue; color: white; text-shadow: 1px 1px 1px black; border-radius: 6px; box-shadow: 0px 0px 5px red; }
    #count, #offset, #showCardsCount, #go, #selectedSpeed { clear: left; }
    .countVar, .offsetVar, .hideme { width: 100px; padding: 0px; margin: 0px; text-align: right; display: inline-block; cursor: pointer; }
    .speed { min-width: 36px; max-width: 36px; width: 36px; display: inline-block; text-align: right; margin-right: 1px; }
    
    /* Over-rides */
    #strategy { background-color: #48707E; border: 1px solid white; box-shadow: 1px 1px 1px #575656; }
    #go { font-family: monospace; box-shadow: 1px 1px 1px #575656; border: 1px solid black; background-color: #E6E6E6; color: #A2BCC6; text-transform: uppercase; letter-spacing: 2px; cursor: pointer; font-weight: bold; }

    #go:hover { background-color: #EFEFEF; }
</style>
</head>

<body>
    <div id="container">
        <div id="card"></div>
        <div id="outputContainer">
            <div id="strategy"><?=$strat?></div>
            <div id="showCardsCount">Show <input id="cardMax" type="number" min="1" max="52" value="18" /> cards</div>
            <div id="selectedSpeed">Selected speed: <span class="speed">2000</span>ms <span class="prevSpeed">-</span> <span class="nextSpeed">+</span></div>
            <div id="offset"><span class="offsetVar">0</span><span class="hideme" data-var="offsetVar">◄</span></div>
            <div id="count"><span class="countVar">0</span><span class="hideme" data-var="countVar">◄</span></div>
            <div id="go">Begin</div>
        </div>
    </div>
    <div id="vars" data-cards="<?= $cardNameString ?>" data-suits="<?= implode(',', $suitArray) ?>" data-show="18" data-timers="3000,2000,1000,750,500,333,250" data-selectedspeed="2000" data-currentcount="0"></div>
</body>

<script type="text/javascript">
    $(document).ready(function(){
        $('#outputContainer').on( 'click', '#go', begin )

        $('#cardMax').on( 'change', function() {
            cardMaxValue = parseInt($('#cardMax').val());
            $('#vars').data('show', cardMaxValue);
        })

        $('#selectedSpeed').on( 'click', '.prevSpeed', function() {
            currentSpeed = parseInt($('.speed').html());
            if (currentSpeed != 250) {
                speedArray = $('#vars').data('timers').split(',');
                $(speedArray).each( function( index, value ) {
                    thisSpeed = parseInt(value);
                    newSpeed = 0;
                    if (thisSpeed==currentSpeed && newSpeed==0) { newSpeed = speedArray[index+1]; $('.speed').html(newSpeed); $('#vars').data('selectedspeed', newSpeed); }
                })
            }
        })

        $('#selectedSpeed').on( 'click', '.nextSpeed', function() {
            currentSpeed = parseInt($('.speed').html());
            if (currentSpeed != 3000) {
                speedArray = $('#vars').data('timers').split(',');
                $(speedArray).each( function( index, value ) {
                    thisSpeed = parseInt(value);
                    newSpeed = 0;
                    if (thisSpeed==currentSpeed && newSpeed==0) { newSpeed = speedArray[index-1]; $('.speed').html(newSpeed); $('#vars').data('selectedspeed', newSpeed); }
                })
            }
        })

        $('.hideme').on( 'click', function() {
            whatAmIHiding = $(this).data('var');
            currentIcon = $(this).html();
            if (currentIcon == "◄") {
                // hide whatAmIHiding
                $('.' + whatAmIHiding).css({ 'color': '#902424', 'text-shadow': '0px 0px 0px #902424' })
                $(this).html('►');
            } else {
                // show whatAmIHiding
                $('.' + whatAmIHiding).css({ 'color': 'white', 'text-shadow': '0px 0px 0px black' })
                $(this).html('◄');
            }
        })

    })

    function begin() {
        //reset visible variables
        $('.countVar, .offsetVar').html(0);
        seconds = 5;
        initialTimeout = seconds * 1000; // 5 seconds before first card is shown
        console.log("Starting in " + seconds + "s");

        strategies = {
            'Hi-Lo':     [ 1,1,1,1,1,1,0,0,0,-1,-1,-1,-1 ],
            'Hi-Opt I':  [ 0,0,1,1,1,1,0,0,0,-1,-1,-1,-1 ],
            'Hi-Opt II': [ 0,1,1,2,2,1,1,0,0,-2,-2,-2,-2 ],
            'KO':        [ -1,1,1,1,1,1,1,0,0,-1,-1,-1,-1 ],
            'Omega II':  [ 0,1,1,2,2,2,1,0,-1,-2,-2,-2,-2 ],
            'Red 7':     [ -1,1,1,1,1,1,0x1,0,0,-2,-2,-2,-2 ],
            'Halves':    [ -1,0.5,1,1,1.5,1,0.5,0,-0.5,-1,-1,-1,-1 ],
            'Zen Count': [ -1,1,1,2,2,2,1,0,0,-2,-2,-2,-2 ]
        }
        selectedStrategy = strategies['<?=$strat?>'];
        selectedStrategyName = '<?= $strat ?>';
        selectedSpeed = parseInt($('#vars').data("selectedspeed"));
        showCountMax = parseInt($('#vars').data('show'));
        cardsArray = $('#vars').data('cards').split(',')
        
        for (counter=0; counter<showCountMax; counter++) {
            timeOut = initialTimeout + (counter * selectedSpeed)
            setTimeout( function(y) {
                currentCardArray = cardsArray[y].split('-');
                currentCard = parseInt(currentCardArray[0]);
                if (selectedStrategyName!='Red 7') {
                    cardCount = parseInt($('.countVar').html());
                    cardCountOffset = selectedStrategy[(currentCard-1)];
                    cardCount = cardCount + cardCountOffset;
                    $('.countVar').html(cardCount);
                    $('.offsetVar').html(cardCountOffset);
                }
                if (currentCard>10 || currentCard == 1) {
                    switch (currentCard) {
                        case  1: currentCard = "A";
                        case 11: currentCard = "J"; break;
                        case 12: currentCard = "Q"; break;
                        case 13: currentCard = "K"; break;
                    }
                }
                suit = currentCardArray[1];
                image = currentCard + suit;
                $('#card').css('background-image', 'url("images/' + image + '.png")');
            }, timeOut, counter)
        }
    }
</script>
</html>