<link rel="stylesheet" type="text/css" href="../assets/styles/bootstrap.css" media="all">
<link rel="stylesheet" type="text/css" href="../assets/styles/style.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../assets/fonts/css/fontawesome-all.min.css">    
<!--link rel="manifest" href="../assets/scripts/_manifest.json" data-pwa-version="set_in_manifest_and_pwa_js"-->
<link rel="apple-touch-icon" sizes="180x180" href="../assets/app/icons/icon-192x192.png">
<link href="https://vjs.zencdn.net/7.17.0/video-js.css" rel="stylesheet" />
<!-- Fantasy -->
<link href="https://unpkg.com/@videojs/themes@1/dist/sea/index.css" rel="stylesheet">
<style>
.radio-view{
  margin: auto;
  padding: 0;
  position:relative;
  max-width:520px;

}


.iphone {
  background: #ffffff;
  border-radius: 1em;
  box-sizing: border-box;
  padding: 2em;
  display: flex;
  flex-direction: column;
}
.iphone .title {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  font-size: 0.75em;
  margin-bottom: 2em;
}
.iphone .album-cover {
  box-sizing: border-box;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.iphone .album-cover img {
  width: 160px;
  height: 156px;
  border-radius: 50%;
}
.iphone .album-cover .song-title {
  
  margin-top:10px;
  text-align: center;
  padding-bottom: 0;
  margin-bottom: 0;
  color: #65717e;
}
.iphone .album-cover .artist-title {
  text-align: center;
  margin-top: 1em;
  padding: 3px 20px 3px 20px;
  font-size: 1em;
  color: #ffffff;
  background-color: #3333ff;
  border-radius: 5px;
}
.iphone .track {
  margin-top: 1em;
  height: 10px;
}
.iphone .track div {
  width: 5%;
  height: 100%;
  background: #3333ff;
  opacity: 0.75;
  border-radius: 15px;
}
.iphone .buttons {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 1em 0;
}
.iphone .lyrics {
  color: #7e8a98;
  margin-top: 2em;
  text-align: center;
  font-size: 0.75em;
  display: flex;
  flex-direction: column;
}

.neu {
  box-shadow: -5px -5px 15px 0px #ffffff9e, 5px 5px 15px 0px #a3b1c6a8;
  background: #e0e5ec;
  border-radius: 2em;
  border: 0;
}

.btn-r {
  padding: 15px 20px 15px 20px;
  border-radius: 30px;
  color: #7e8a98;
  outline: none;
  cursor: pointer;
  margin:0 25px 0px 25px;
}
.btn-r.lg {
  font-size: 1em;
}
.btn-r:hover {
  cursor: pointer;
  background: #eff2f5;
}

.red {
  color: #e22d49;
}
.spin {
  -webkit-animation-name: spin;
  -webkit-animation-duration: 20000ms;
  -webkit-animation-iteration-count: infinite;
  -webkit-animation-timing-function: linear;
  -moz-animation-name: spin;
  -moz-animation-duration: 20000ms;
  -moz-animation-iteration-count: infinite;
  -moz-animation-timing-function: linear;
  -ms-animation-name: spin;
  -ms-animation-duration: 20000ms;
  -ms-animation-iteration-count: infinite;
  -ms-animation-timing-function: linear;
  -o-transition: rotate(3600deg);
}
@-moz-keyframes spin {
    from { -moz-transform: rotate(0deg); }
    to { -moz-transform: rotate(360deg); }
}
@-webkit-keyframes spin {
    from { -webkit-transform: rotate(0deg); }
    to { -webkit-transform: rotate(360deg); }
}
@keyframes spin {
    from {transform:rotate(0deg);}
    to {transform:rotate(360deg);}
}

</style>