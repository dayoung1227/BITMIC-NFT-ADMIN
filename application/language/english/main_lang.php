<?php
defined('BASEPATH') OR exit('No direct script access allowed');


//투자금 지급 안내
$lang['main_txt00'] = COIN_NAME.' Payment Guide';
//구매하신 토큰은 2018년 9월 18일까지 홈페이지에 등록하신 지갑으로 일괄 전송 완료될 예정입니다.';
$lang['main_txt01'] = 'The purcharsed coin will be sent to the wallet registered In up to 30 minutes.';
//구매하신 TONE을 지갑에서 찾을 수 없다면, 다음 사항을 확인해 주십시오. 
$lang['main_txt02'] = 'If you cannot find the purchased '.COIN_NAME.' in your wallet, please check the following:';
//1.KYC 통과여부
$lang['main_txt03'] = '1. The minimum amount you can participate is at least 0.1.';
//2.투자 시 사용한 지갑 주소와 등록한 지갑 주소의 일치 여부
$lang['main_txt04'] = '2. Whether the address of the wallet used in the investment matches that of the registered wallet';
//투자금에 문제가 있을 시, triponeio@gmail.com 으로 문의 바랍니다.
$lang['main_txt05'] = 'If there are any problems, please contact <a href="mailto:'.MAIL_USER.'">'.MAIL_USER_NM.'</a>';

//구매량
$lang['main_txt06'] = 'Purchase quantity';
//참여한 Ethereum 수량
$lang['main_txt07'] = 'Ethereum quantity of participated';
//지급 대기중인 코인
$lang['main_txt06-01'] = 'Waiting '.COIN.' coin';
//지급 대기중인 코인
$lang['main_txt07-01'] = 'Amount of Wait for payment';

//지급한 코인
$lang['main_txt06-02'] = 'Your '.COIN.' coin';
//지급한 코인
$lang['main_txt07-02'] = 'Amount of Total '.COIN.' coin';



//최소 구매 한도는 0.1ETH 입니다. ETH 수량을 입력하면, 자동으로 TONE 를 계산해드립니다.
$lang['main_txt08'] = 'Minimum purchase limit is 0.1ETH. '.COIN_NAME.' will be calculated automatically upon entering the ETH quantity)';
//Coin Market Cap 기준
$lang['main_txt09'] = 'Coin Market Cap As of ';
//수량을 입력하세요
$lang['main_txt10'] = 'Please enter quantity';
//Ethereum 주소 등록
$lang['main_txt11'] = 'Your Ethereum Wallet Address';
//TRIPONE 프리세일 및 ICO에 참여 하기 위해서는 저희 대시보드에 개인 이더리움 지갑 주소를 등록해 주셔야 합니다. 저희는 MyEtherWallet를 권장해드리고 있습니다.
$lang['main_txt12'] = 'In order to participate in the '.SITE_NAME.' pre-sales and ICO, you need to register your personal Ethereum wallet address on our dashboard. We recommend MyEtherWallet.';
//블록체인 거래소에서 제공받은 지갑주소를 등록하실 경우, 제대로 참여하실 수 없을 수 있습니다. 유의 바랍니다.
$lang['main_txt12-1'] = 'If you register the wallet address provided by the block chain exchange, you may not be able to participate properly.';

//한번 등록된 이더리움 지갑 주소는 수정이 불가능하오니 신중하게 입력해주세요.
$lang['main_txt13'] = 'Please fill out your entry with caution as the Ethereum wallet address cannot be modified once registered.';
//Ethereum 지갑 주소
$lang['main_txt14'] = 'Ethereum Wallet Address';
//이더리움 지갑 주소를 입력하세요
$lang['main_txt15'] = 'Please enter Ethereum wallet address';
//TRIPONE ICO 주소(Ethereum Wallet 주소)
$lang['main_txt16'] = 'OUR '.SITE_NAME.' ICO Wallet Address(Ethereum Wallet Address)';
//절대 거래소 지갑(빗썸, 업비트 등)을 이용하지 마세요. 이더리움 지갑 만드는 법은 다음을 참고하세요.
$lang['main_txt17'] = 'Never use exchange wallets (Bithumb, Upbit, etc.). Please refer to the following for instructions on how to create the Ethereum wallet ';
//클릭
$lang['main_txt18'] = 'click';
//거래소 지갑에서 보낼 경우, TONE 토큰을 받지 못하거나 복구할 수 없을 수도 있습니다.
$lang['main_txt19'] = 'If you send it from your exchange wallet, you may not receive an '.COIN_NAME.' coin or be able to recover it.';
//TRIPONE ICO 주소(Ethereum Wallet)는 
$lang['main_txt20'] = SITE_NAME.' ICO Wallet Addres(Ethereum Wallet) is ';
//TONE 지갑 주소 등록
$lang['main_txt21'] = COIN_NAME.' Wallet Address Registration';
//이더리움을 투자하여 얻게 될 TONE을 지급 받을 주소를 제출해 주세요. 저희가 배포한 지갑이어야 하며, 타 지갑 주소는 허용하지 않습니다. Coinbase, GDax, Bitterex, Biance 혹은 다른 거래소 지갑 주소를 입력하게 된다면 코인을 지급 받지 못할 수도 있습니다.
$lang['main_txt22'] = 'Please submit an address to receive the '.COIN_NAME.' you will acquire for your Ethereum investment. It must be a wallet distributed by us and we do not allow other wallet addresses. You may not receive your coin payment if you enter Coinbase, GDax, Bittrex, Binance, or other exchange wallet addresses';
//단, TONE을 지급받기 위해선 KYC 인증을 완료하셔야 하며, KYC 인증을 하지 않을 시 정상적으로 TOKEN이 지급되지 않을 수 있습니다.
$lang['main_txt23'] = 'However, you must complete the KYC verification in order to receive '.COIN_NAME.' payment. Without KYC verification, COIN may not be paid normally.';
//현재상태
$lang['main_txt24'] = 'Current Status';
//KYC 미인증
$lang['main_txt25'] = 'KYC Unverified';
//KYC 인증
$lang['main_txt26'] = 'KYC verified';
//KYC 인증 바로가기
$lang['main_txt27'] = 'Proceed directly to KYC verification';
//TONE 지갑 주소
$lang['main_txt28'] = 'Your '.COIN_NAME.' Wallet Address';
//TONE Wallet 주소를 입력하세요
$lang['main_txt29'] = 'Please Enter '.COIN_NAME.' Wallet Address';

//실시간 TONE 변환
$lang['main_txt30'] = COIN_NAME.' Calculator';
//토큰 컨트랙트 주소
$lang['main_txt31'] = COIN_NAME.' TOKEN Contract Address';
//토큰 컨트랙트 주소는 아래와 같습니다. 보유하신  지갑(IM TOKEN, METAMASK, MyEtherWallet)에 아래의 내용을 넣어 토큰을 추가해주세요
$lang['main_txt32'] = COIN_NAME.' Token Contract Address is shown below. Add your token to your wallet (IM TOKEN, METAMASK, MyEtherWallet) with the following information';
$lang['main_txt33'] = COIN_NAME.' Token Contract Address : ';
$lang['main_txt34'] = COIN_NAME.' Token Symbol : ';
$lang['main_txt35'] = COIN_NAME.' Decimal : ';

$lang['main_txt36'] = 'My ICO participation History';


$lang['mail_txt01'] = 'Thank you for joining '.SITE_NAME;
$lang['mail_txt02'] = 'This is only to verify that your registered email is correct.To complete this process, please click below button.';
$lang['mail_txt03'] = 'Unless complete this verification, <br>your bounty program is not valid.';
$lang['mail_txt04'] = 'ACTIVATE ACCOUNT';
$lang['mail_txt05'] = 'This email address is not allowed to reply.<br>If you need any help, please send an email to '.MAIL_USER;

$lang['mail_txt06'] = 'Create Temporary Password';
$lang['mail_txt07'] = 'This e-mail is in response to your recent request to recover a fogotten password.  Password security features are in place to ensure the security of your profile information.  To reset your password, please click the link below and follow the instructions provided.';
$lang['mail_txt08'] = 'Your new temporary password : ';
$lang['mail_txt09'] = 'reset your password';
$lang['mail_txt10'] = 'Also You can access old password because Someone may have reset the password for malicious purposes.';
$lang['mail_txt11'] = 'If you are having trouble accessing the link, try open the link address another Internet browser.';