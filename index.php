<?php

$arr = array();
$noBullshitStrings = array();
$bullshitStrings = array();

$credentials = getCredentials('https://koodihaaste-api.solidabis.com/secret');

if ((isset($credentials->{'jwtToken'})) && (isset($credentials->{'bullshitUrl'}))) {
    $data = getData($credentials->{'bullshitUrl'}, $credentials->{'jwtToken'});

    if ((isset($data->{'bullshits'}) && $dataArray = $data->{'bullshits'})) {
        foreach ($dataArray as $value) {
            if ((isset($value->{'message'}) && $message = $value->{'message'})) {

                $messageList = decodeCaesar($message);

                $currentKey = 0;
                $validCounter = 0;
                foreach ($messageList as $key => $values) {
                    if ($values['validCounter'] > $validCounter) {
                        $currentKey = $key;
                        $validCounter = $values['validCounter'];
                    }
                }

                $arr[] = $messageList[$currentKey];

                if ($validCounter >= 9) {
                    $noBullshitStrings[] = $messageList[$currentKey]['message'];
                } else {
                    $bullshitStrings[] = $message;
                }
            }
        }
    }
}

echo '<pre>';
echo '<h1>No Bullshit Array</h1>';
print_r($noBullshitStrings);
echo '<br><br>';
echo '<h1>Bullshit Array</h1>';
print_r($bullshitStrings);
echo '<pre>';


//-------------------------FUNCTIONS/-------------------------


/**
 * Get data from URL by JWT Token
 * 
 * @param string $url
 * @param string $jwtToken
 * @return stdClass
 */
function getData($url, $jwtToken)
{
    $ch = curl_init($url);

    $authorization = "Authorization: Bearer " . $jwtToken;
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);
    curl_close($ch);

    return json_decode($result);
}

/**
 * Get credentials from URL
 * 
 * @param string $url
 * @return stdClass
 */
function getCredentials($url)
{
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);
    curl_close($ch);

    return json_decode($result);
}

/**
 * Get credentials from URL
 * 
 * @param string $hash
 * @return array
 */
function decodeCaesar($hash)
{
    $values = array();

    $letter = array(
        'a',
        'b',
        'c',
        'd',
        'e',
        'f',
        'g',
        'h',
        'i',
        'j',
        'k',
        'l',
        'm',
        'n',
        'o',
        'p',
        'q',
        'r',
        's',
        't',
        'u',
        'v',
        'w',
        'x',
        'y',
        'z',
        'å',
        'ä',
        'ö'
    );

    $validationStrings = array(
        ' on ',
        ' ja ',
        ' ei ',
        ' tai ',
        ' hän ',
        ' ehkä ',
        ' mitä ',
        ' sinä ',
        ' minä ',
        ' te ',
    );

    $stringArray = explode(" ", "erotuksena muunlaisista asumuksista kuten kodasta tai majasta talo on tarkoittanut suoraseinäistä jykevämpää rakennusta jonka seinät ovat yleensä hirsiset saanut nimensä talaasta eräisiin muinaisiin taloihin kuuluneesta pylväiden päässä olevasta lavasta jolla esimerkiksi kuivattu heinäälähde vanhimmat rakennukset olivat ilmeisesti kotia kuitenkin jo kivikaudella suomessa tehty myös isokokoisia rakennuksia hirsisalvostekniikan avulla oli osittain tuettu maa aineksella kaivettu maan sisään talot jopa kymmeniä metrejä pitkiä rivitalomaisia usean perheen yhden sukuklaanin asumuksia perinne väliin katkennut vuoksi siirryttiin käyttämään kotaa ja uutta tulokasta majaa uudelleen hirsitalot tulivat käyttöön metallikausillaselvennä talon uuden tulemisen jälkeen käytetty enää tilapäisasumuksena puolestaan keittopaikkana jossa ruokaa erityisesti kesällätalot pitkään maalattiaisia voineet olla esim hirttä lautaa turvetta savea vanhimmissa taloissa ollut joko nuotiopaikka keskellä lattiaa ruoka valmistettu erillisessä keittokodassa uunit savupiiput yleistyivät vasta keskiajallatalo sanan käyttö jossain vaiheessa laajentunut tarkoittamaan kokonaista maatilaa kaikkine rakennuksineen talonomistajan omistamaa kaikkea alaa termi talollinen kuvastaa maatilan omistusta ei asumuksen tyyppiä torpparin taloa kutsutaan torpaksi sillä kertoo henkilön asuvan vuokramaalla mäkitupalaisen mäkitupa vaikkei se välttämättä mitenkään eronnut vaatimattoman talollisen asumuksesta talosta työnsä ohella von kleist harrasti luonnontieteellisiä kokeita keksi vuonna 1745 kleistin pullon ensimmäisen sähköä varastoivan laitteen huomatessaan tehoalisäävän toiminnan leidenissä kokeellisen fysiikan professori pieter van musschenbroek vähän myöhemmin saman ilmiön omin neuvoin hänen julkistettuaan sen tämä ensimmäinen kondensaattori tuli tunnetuksi nimellä leidenin pullo vaikka olikin varhainen keksijä hän julkistanut laitettaan ajoissa eikä sitten kehittänyt sitä eteenpäin niin että nykyajan tutkijat antavat suurimman kunnian kondensaattorin alkuvuosien kehitystyöstä musschenbroekille elokuva sai parhaan elokuvan oscar palkinnon silloisen tarinan oscarin joka yhdistettiin alkuperäisen käsikirjoituksen palkintoon varsinkin valintaa arvosteltu ehdolla klassikoksi noussut sheriffi epäsi graafisen suunnittelun opiskelupaikan kuulovammaiselta opiskelijalta opiskelija jätti kokonaan kertomatta koululle erityistarpeistaan viittomakielen tulkin otettua yhteyttä kouluun alle viikkoa ennen opintojen alkua koulu ilmoitti ettei ole yksityisenä kouluna resursseja valmistautua syntymäkuuron opiskelijan erityistarpeisiin lyhyellä varoitusajalla kela suosittelee opiskelutulkin tilaamista hyvissä ajoin joululahja kääritty joulupaperiin koristavat kauniit lahjanauhat narut lahjapaketissa voi sinetti lahjansaajan nimi hyvän joulun toivotus sekä lahjan antajan lapsille uskotellaan tuhmille joulupukki tuo lahjoja vaan risuja suomalaisessa kultturissa joululahjoja jaetaan pikkujouluperinteessä osallistujat ostavat sovitun hintaisen tavaran lahjaksi lahjat satunnaisesti pikkujouluihin osallistuville ihmisille toisinaan lahjansaajat etukäteen arvottu antaja tietää kenelle hankkii kaksi tapaa lahjojen jakoon useimmiten tulee taloon oveen koputtaen keskustelee joskus kanssa ottaa kontistaan kerrallaan katsoo saajan nimen nimeltä kutsuen ojentaa saajalle perheenjäsen pukin mukana kulkeva tonttu avustaa joulupukkia jaossa toisen tavan mukaan asetetaan kuusen joulupukin sanotaan käyneen välin kun perhe muualla työllä tarkoitetaan jonkin tehtävän suorittamiseen tähtäävää pitkäjännitteistä aktiivista tavoitteellista toimintaa liittyy tyypillisesti toimeentulon hankkimiseen ansiotyö yksilön omien taikka lähipiirinsä aineellisten tarpeiden tyydyttämiseen kotityö Muunlaista työtä vapaaehtoistyöksi työn ensisijaisena motiivina rentoutuminen saattaa laita käsitöissä puutarhanhoidossa jako ruumiilliseen henkiseen työhön määrin harhaanjohtava koska lähes kaikessa työssä olennaisena edellytyksenä älykkyys kokemuksen opettamat seikat asumiseen toimisto liike muuhun vastaavaan tarkoitettu rakennus politiikan alaan sisällytetään oikeusvaltiossa lähinnä lainsäädännön muuttamiseen julkisen hallinnon ohjaamiseen tähtäävä toiminta poliittisen vastakohtana perinteisesti nähty yksityinen eli katsota liittyvän muihin yksilöihin kreikan kielessä merkitsi taloutta sana oikos josta sittemmin johdettu ekonomia taloustiede iskulause henkilökohtainen poliittista 1960 luvulla kaikkia yksilöiden välisiä suhteita koskevia asioita pitäisi tuoda valtapolitiikan aiheiksi alun perin kiinnitti huomiota siihen ihmisten välisissä jokapäiväisissä suhteissa toteutetaan pidetään yllä poliittisesti moraalisesti tärkeitä siksi pitää ulottua arkeen politiikka juontuu antiikin kielen sanasta politikos kansalaisia koskevaa alkuperältään käytännön teorian vaikuttamista toisiin ihmisiin globaalilla kansallisella yksilöllisellä tasolla nykyisessä yleiskielessä sanalla usein johonkin tiettyyn kokonaisnäkemykseen aatteeseen pohjautuvaa pyritään vaikuttamaan valtiollisiin valtioiden välisiin yhteiskunnallisiin asioihin hoidetaan niitä sanaa käytetään puolueiden toiminnasta politiikkaa päätöksentekoa tutkiva yhteiskuntatiede nimeltään politologia tutkimus valtio oppi viimeksi mainittu saksan termistä staatslehre allgemeine yleinen anglosaksisissa maissa termiä political science joskin politics käytössä teoriaa poliittisia aatteita filosofiaa tutkitaan tutkimuksen monissa yliopistoissa käytännöllisen filosofian piirissä tavallaan kaikki yhteiskuntatieteet tutkivat piiriin kuuluvia päätöksentekojärjestelmiä esiintyy erilaisia edustuksellisessa demokratiassa äänioikeusikärajan saavuttaneet osallistuvat päätöksentekoon valitsemiensa edustajien heille vastuunalaisten hallituselinten kautta perustuslaillinen tasavalta kansanvallan rajoitetumpi muoto yksinvallassa diktatuurissa yleisesti ottaen johtajavaltaisissa autoritaristisissa valtiomuodoissa yksi henkilö ryhmä päättää kaikkien asioista tarkastelun tekee ongelmalliseksi mitä kansanvaltaisin perustuslaillisin järjestelmä itse asiassa räikein diktatuurilähde oligarkia harvainvaltaa aristokratia ylhäisön muun parhaimmiston valtaa jonkinasteista oligarkiaa aristokratiaa tavataan demokraattisimmissakin järjestelmissä mikäli rajoitetaan vähimpään mahdolliseen yritetään jättää asiat yksittäisten päätettäviksi kyseessä libertarismi valtion poistuessa anarkia viimeiset aatetta eivät toisensa poissulkevia kummankin niistä sovellutukset jääneet harvoiksi lyhytaikaisiksi mutta teoreettisina malleina historiallisina ajattelutapoina niillä silti oma kiinnostavuutensa viime viikkoina väkivaltaiseksi muuttuneet mielenosoitukset vaikuttivat lauantaina päivällä rauhoittuneen neljän auton kolari aiheutti aamulla valtavan liikenneruuhkan kehätiellä aiheesta tietämättömälle sukukypsä ankerias hämmästyttävä pelottava näky lokki kirkuu ryöstää nakkisämpylän kädestä sotkee ulosteellaan paikkoja työntekijämme saavat vähintään samat edut kuin muissakin alan yrityksissä ilman hierarkiaa byrokratiaa syyttäjä vaatii vuoden ehdotonta vankeusrangaistusta rahtialuksen kapteenille törkeästä veropetoksesta hovioikeudessa valelääkärin tempauksista kunnalle aiheutuneet taloudelliset vahingot arvioitiin odotettua pienemmiksi pelastusjoukot etsineet kateissa olleita ihmisiä myrskyn aiheuttaman maanvyöryn alta jos suinkin aikaa pitkän matkan päätteeksi juodaan yhteiset kahvit keittiön pöydän ääressä työelämässä odotetaan nykyään sosiaalisuutta kykyä esiintyä ainakin tarvittaessa uskaltaa avata suunsa kokouksissa siellä muisteltiin hymyssä suin tapausta lääkäri osannutkaan pukea vanhukselle tukisukkia takaisin jalkaan ilmastonmuutos tehdä vakuuttamisen mahdottomaksi pahimmilla riskivyöhykkeillä elostelevasta elämänkerrasta rehentelyn sivumaku saa sapen kiehumaan kohtaamisen ohessa papilla tutkia hautausmaata hautoja niihin liittyviä elämäntarinoita teknologia vaikuttaa tulevaisuudessa tarvittavien taitojen lisäksi opetuksen oppimisen tapoihin vankilasta saadut tiedot herättäneet epäilyjä valvonta riittävän tarkkaa arjen tavat muodostuvat toistamalla toistojen luontevaa hyvänsä");


    $validationStrings = array_merge($validationStrings, $stringArray);

    $hash = mb_strtolower($hash);

    for ($i = 0; $i < count($letter); $i++) {
        $message = '';

        $chars = preg_split('//u', $hash, null, PREG_SPLIT_NO_EMPTY);
        foreach ($chars as $char) {

            if (($char !== " ") && ($char !== ".") && ($char !== ",")) {
                $key = array_search($char, $letter);
                $newKey = $key + $i;

                if ($newKey < count($letter)) {
                    $char = $letter[$newKey];
                } else {
                    $newKey = (($newKey - count($letter)) == 29 ? 0 : ($newKey - count($letter)));
                    $char = $letter[$newKey];
                }
            }

            $message .= $char;
        }

        $validCounter = 0;
        foreach ($validationStrings as $word) {
            $validCounter = $validCounter + substr_count($message, $word);
        }

        $values[] = array(
            'validCounter' => $validCounter,
            'message' => $message,
        );
    }

    return $values;
}


//-------------------------/FUNCTIONS-------------------------
