<?php
// SET UP VARS
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
    #container { width: 1900px; }
    #card { background-size: contain; background-image: url("images/00.png"); width: <?= round(691*0.35) ?>px; height: <?= round(1056*0.35) ?>px; float: left; }
    #outputContainer, #strategy, #count, #offset, #showCardsCount, #selectedSpeed, #gameOptions, #playerOptions, .button {  float: left; }
    #strategy, #count, #offset, #showCardsCount, #selectedSpeed, #gameOptions, #playerOptions, .playerTitle, .button { margin: 5px 15px; padding: 5px 15px; width: 200px; text-align: center; background-color: #902424; border: blue; color: white; text-shadow: 1px 1px 1px black; border-radius: 6px; box-shadow: 0px 0px 5px red; }
    #count, #offset, #showCardsCount, #go, #selectedSpeed, #gameOptions, #playerOptions, .playerTitle { clear: left; }
    .prevSpeed, .nextSpeed { cursor: pointer; }
    #gameOptions { margin-top: 42px; }
    #playerNumber, #totalPlayers { margin-left: 15px; width: 26px; }
    .countVar, .offsetVar, .hideme { width: 100px; padding: 0px; margin: 0px; text-align: right; display: inline-block; cursor: pointer; }
    .speed { min-width: 36px; max-width: 36px; width: 36px; display: inline-block; text-align: right; margin-right: 1px; }
    #playerContainer, #player0, #player1, #player2, #player3, .card1, .card2, .card3, .card4, .card5 { float: left; }
        #player0, #player1, #player2, #player3 {  padding: 0px; margin: 5px 10px; }
        .card { background-size: contain; width: <?= round(691*0.185) ?>px; height: <?= round(1056*0.185) ?>px; background-color: black; border-radius: 10px; border: 1px dashed white; position: relative; }
        .count { padding: 15px 15px; font-size: 100px; color: white; text-shadow: 1px 1px 1px black; float: left; position: absolute; margin-left: 27px; margin-top: 25px; transform: rotate(-12deg); }
    
    /* Over-rides */
    #strategy, #gameOptions { background-color: #48707E; border: 1px solid white; box-shadow: 1px 1px 1px #575656; }
    .button { font-family: monospace; box-shadow: inset 0px 0px 14px 2px #000000; border: 1px solid white; background-color: #455957; color: #A2BCC6; text-transform: uppercase; letter-spacing: 2px; cursor: pointer; font-weight: bold; }
    .button:hover { background-color: #EFEFEF; }
    .small { width: 60; margin-left: 10px; margin-right: 5px; }

    .playerTitle { background-color: #48707E; width: 290px; border: 1px solid white; margin: 0px; margin-bottom: 10px; box-shadow: none; }
    #player0 .playerTitle { background-color: #902424; }
    .player { max-width: 320px; }

    body { -webkit-touch-callout: none; -webkit-user-select: none; -khtml-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none;}
    #options { display: none; }
</style>
</head>

<body>
    <div id="container">
        <div id="card"></div>
        <div id="outputContainer">
            <div id="strategy">Strategy: <?=$strat?></div>
            <div id="showCardsCount">Show <input id="cardMax" type="number" min="1" max="32" value="6" /> cards</div>
            <div id="selectedSpeed">Selected speed: <span class="speed">250</span>ms <span class="prevSpeed">-</span> <span class="nextSpeed">+</span></div>
            <div id="offset"><span class="offsetVar">0</span><span class="hideme" data-var="offsetVar">◄</span></div>
            <div id="count"><span class="countVar">0</span><span class="hideme" data-var="countVar">◄</span></div>
            <div id="gameOptions">Game Options</div>
            <div id="playerOptions">Player <input id="playerNumber" type="number" min="1" max="3" value="1"/> of <input id="totalPlayers" type="number" min="1" max="3" value="3"/></div>
            <div id="go" class="button">Begin</div>
        </div>
    
        <div id="playerContainer">
            <div id="player0" class="player" data-hand="" data-handcount="0" data-acecount="0">
                <div class="playerTitle">PLAYER 1</div>
                <div class="card1 card" data-count=""></div>
            </div>
            <div id="player1" class="player" data-hand="" data-handcount="0" data-acecount="0">
                <div class="playerTitle">PLAYER 2</div>
                <div class="card1 card" data-count=""></div>
            </div>
            <div id="player2" class="player" data-hand="" data-handcount="0" data-acecount="0">
                <div class="playerTitle">PLAYER 3</div>
                <div class="card1 card" data-count=""></div>
            </div>
            <div id="player3" class="player" data-hand="" data-handcount="0" data-acecount="0">
                <div class="playerTitle">DEALER</div>
                <div class="card1 card" data-count=""></div>
            </div>
            <div id="options">
                <div id="hitMe" class="small button">HIT</div><div id="split" class="small button">SPLIT</div><div id="stay" class="small button">STAND</div>
            </div>
        </div>
    </div>
    <div id="vars" data-currenthighscore="0" data-dealerscore="0" data-dealerbust="no" data-currentplayer="0" data-humanplayer="" data-cards="<?= $cardNameString ?>" data-cardsleft="" data-usedcards="" data-suits="<?= implode(',', $suitArray) ?>" data-show="3" data-timers="3000,2000,1000,750,500,333,250" data-selectedspeed="250" data-currentcount="0"></div>
</body>

<script type="text/javascript">
    $(document).ready(function(){
        // INIT
        maxShow = parseInt($('#vars').data('show'));
        $('#cardMax').attr('value', maxShow);
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

        $('#playerOptions').on( 'click', '#playerNumber', function() {
            playerNumber = parseInt($('#playerNumber').val());
            totalPlayers = parseInt($('#totalPlayers').val());
            if (playerNumber > totalPlayers) { $('#totalPlayers').val(playerNumber); }
        })
        $('#playerOptions').on( 'click', '#totalPlayers', function() {
            playerNumber = parseInt($('#playerNumber').val());
            totalPlayers = parseInt($('#totalPlayers').val());
            if (totalPlayers < playerNumber) { $('#playerNumber').val(totalPlayers); }
        })

        $('#hitMe').on( 'click', function() {
            player = $('#vars').data('currentplayer');
            hitMe(player);
        })
        $('#stay').on( 'click', function() {
            player = parseInt($('#vars').data('currentplayer'));
            showFinalCount(player);
        })

        // stop the user being able to type into the option boxes whilst leaving the increase/descrease buttons enabled
        $('#cardMax, #playerNumber, #totalPlayers').on( 'keydown', function(e) { e.preventDefault(); })
    })

    function begin() {
        //reset visible variables
        $('.countVar, .offsetVar').html(0);
        seconds = 0;
        initialTimeout = seconds * 1000; // x seconds before first card is shown
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
        cardsArray = $('#vars').data('cards').split(',');
        playerTotal = parseInt($('#totalPlayers').val());
        playerNumber = parseInt($('#playerNumber').val())-1; $('#vars').data('humanplayer', playerNumber);

        cardsToDeal = (playerTotal+1)*2;
        
        for (counter=0; counter<showCountMax+cardsToDeal; counter++) {
            timeOut = initialTimeout + (counter * selectedSpeed);
            player = 0; 
            setTimeout( function(passedCounter) {
                currentCardArray = cardsArray[passedCounter].split('-');
                currentCard = parseInt(currentCardArray[0]);
                if (selectedStrategyName!='Red 7') {
                    // SHOW THE OFFSET TO USER
                    cardCount = parseInt($('.countVar').html());
                    cardCountOffset = selectedStrategy[(currentCard-1)];
                    cardCount = cardCount + cardCountOffset;
                    $('.countVar').html(cardCount);
                    $('.offsetVar').html(cardCountOffset);
                }
                if (currentCard>10 || currentCard == 1) {
                    switch (currentCard) {
                        case  1: currentCard = "A"; handCount=11;  if (passedCounter >= showCountMax) { aceCount = parseInt($('#player'+player).data('acecount')); aceCount++; $('#player'+player).data('acecount', aceCount) }; break;
                        case 11: currentCard = "J"; handCount = 10; break;
                        case 12: currentCard = "Q"; handCount = 10; break;
                        case 13: currentCard = "K"; handCount = 10; break;
                    }
                } else {
                    handCount = currentCard;
                }

                suit = currentCardArray[1];
                image = currentCard + suit;
                usedCards = $('#vars').data('usedcards') + ',' + cardsArray[passedCounter];
                $('#vars').data('usedcards', usedCards);

                if (passedCounter<showCountMax) {                                                   // DEALING OUT CARDS TO DECK FOR COUNTING
                    $('#card').css('background-image', 'url("images/card' + image + '.png")');
                } else {                                                                            // DEALING CARDS TO PLAYERS
                    currentPosition = passedCounter - showCountMax;
                    if (currentPosition == 0) { $('.card').css('border', 'none'); }
                    if (currentPosition%2==0 && currentPosition>0) { player++; }
                    
                    // UPDATE PLAYERS HAND COUNT
                    currentHandCount = parseInt($('#player' + player).data('handcount'));
                    newHandCount = currentHandCount + handCount;
                    if (newHandCount>21 && aceCount>0) {
                        // player current calculation is greater than 21, but they have aces
                        while (newHandCount>21 && aceCount>0) {
                            newHandCount -= 10;
                            aceCount--;
                        }
                    }
                    $('#player' + player).data('handcount', newHandCount);
                    
                    // build card if it doesn't exist card
                    cardPosition = '#player' + player + ' .card' + (currentPosition%2 +1);
                    cardPositionOffset = currentPosition%2 * 85;
                    if ($(cardPosition).length==0) { // does this card position exist? if not create it before changing its background image
                        $('#player'+player).append('<div class="card' + (currentPosition%2 +1) + ' card" data-count="' + handCount + '"></div>');
                    }
                    currentHand = $('#player' + player).data('hand');
                    if (currentHand.length==0) { currentHand = image; } else { currentHand += "," + image }
                    $('#player' + player).data('hand', currentHand);
                    $('#player' + player + ' .card' + (currentPosition%2 +1)).css({ 'background-image': 'url("images/card' + image + '.png")', 'margin-left': -cardPositionOffset, 'border': '1px solid black' });

                    if (passedCounter==showCountMax+cardsToDeal-1) {
                        dealtCards = $('#vars').data('usedcards');
                        dealtCards = dealtCards.substring(1,dealtCards.length) + ",";
                        cardsLeft = $('#vars').data('cards').replace(dealtCards, ''); 
                        $('#vars').data('cardsleft', cardsLeft);
                        console.log('Last card dealt. Player 1s shot.');
                        nextPlayer(-1);
                    }
                }
            }, timeOut, counter)
        }
    }

    function contemplateHand(player=0) {
        //selectedSpeed = parseInt($('#vars').data('selectedspeed'));
        setTimeout( function() {
            if ($('#player'+player).data('handcount')<17) {             // ask for another card
                console.log("Asking for another card"); hitMe(player);
            } else {                                                    // staying
                console.log("Staying"); showFinalCount(player);
            }
        }, 1000)
    }
    function hitMe(player, timeOut=500) {
        cards = $('#player'+player+' .card').length;
        if (cards<5) {
            aceCount = 0;
            cardsLeftArray = $('#vars').data('cardsleft').split(',');
            playersCard = cardsLeftArray.shift();
            // update cards left var
            cardsLeft = cardsLeftArray.join(',');
            $('#vars').data('cardsleft', cardsLeft);
            // update used cards var
            usedCards = $('#vars').data('usedcards') + ',' + playersCard;
            $('#vars').data('usedcards', usedCards);

            // convert card to something we can use
            playersCardArray = playersCard.split('-');
            cardValue = parseInt(playersCardArray[0]);
            cardSuit = playersCardArray[1];
            if (cardValue>10 || cardValue == 1) {
                switch (cardValue) {
                    case  1: cardValue = "A"; aceCount = parseInt($('#player'+player).data('acecount')); handCount = 11; aceCount++; $('#player'+player).data('acecount', aceCount); break;
                    case 11: cardValue = "J"; handCount = 10; break;
                    case 12: cardValue = "Q"; handCount = 10; break;
                    case 13: cardValue = "K"; handCount = 10; break;
                }
            } else {
                handCount = cardValue;
            }
            image = cardValue + cardSuit;
            // update the players hand
            playersHand = $('#player'+player).data('hand') + ',' + image;
            $('#player'+player).data('hand', playersHand)

            // show the new card
            $('#player'+player).append('<div class="card' + (cards+1) + ' card" data-count="' + handCount + '"></div>');
            leftOffset = -85;
            $('#player'+player+' .card'+(cards+1)).css({ 'margin-left': leftOffset + 'px', 'background-image': 'url("images/card' + image + '.png")', 'border': '1px solid black' })

            // figure out if the player is bust or not
            totalCount = $('#player'+player).data('handcount') + handCount;
            if (totalCount>21 && aceCount>0) {
                // player current calculation is greater than 21, but they have aces
                while (totalCount>21 && aceCount>0) {
                    totalCount -= 10;
                    aceCount--;
                }
            }
            $('#player'+player).data('handcount', totalCount);
            if (totalCount>21) { showFinalCount(player); } else { if (player!=$('#vars').data('humanplayer')) { contemplateHand(player); } }
        }
        setTimeout( function() {

        }, timeOut)
    }
    function showFinalCount(player) {
        handCount = parseInt($('#player'+player).data('handcount'));
        bust = handCount>21 ? true : false;

        if (player==3) {
            if (bust==false) { $('#vars').data('dealerscore', handCount); } else { $('#vars').data('dealerscore', 0); handCount=0; }
        } else {
            if (bust==true) { handCount=-1; }
        }
        $('#player'+player).data('handcount', handCount);

        // show user score on top of cards
        if (bust==true) { playerScore='BUST'; fsize=' font-size: 48px;"'; bgCol='#8C0000B3'; } else { playerScore=handCount; fsize=''; bgCol='#00B22DB3'; }
        scoreHTML='<div class="count" style="background-color: ' + bgCol +';' + fsize + '">' + playerScore + '</div>';
        $('#player'+player).append(scoreHTML);
        if (player<=$('#totalPlayers').val()) {
            nextPlayer(player);
        } else {
            console.log('All hands dealt. Checking who won...')
            checkForWinner();
        }
        
    }
    function nextPlayer(player) {
        player++;
        if (parseInt($('#vars').data('humanplayer')!=player)) { $('#option').fadeOut(333); }
        $('#vars').data('currentplayer', player)
        // position the "hand" options
        $('#options').css({'transform' : 'translate(' + (player)*342 + 'px)'});
        if ($('#vars').data('humanplayer')==player) {
            console.log("Users Turn"); $('#options').fadeIn("slow");
        } else {
            console.log("Computers Turn..."); $('#options').fadeOut("slow"); contemplateHand(player);
        }
    }
    function checkForWinner() {
        console.log('checking for winner');
        playerDealerHandCount = $('#player3').data('handcount');
        $('.player').each( function() {
            thisID = $(this).attr('id');
            if (thisID != "player3") {
                handCount = $(this).data('handcount');
                offset = ($('#'+thisID + ' .card').length - 1) *27;
                if (handCount >= playerDealerHandCount && handCount < 22) { bgCol = '#00B22DB3'; text="WIN"; } else { bgCol='#8C0000B3'; text="LOSE"; }
                $('#'+thisID+' .count').css({ 'background-color': bgCol, 'font-size': '32px', 'margin-top': "55px", 'margin-left': offset + 'px' }).html(text);
            }
        })
    }

</script>
</html>