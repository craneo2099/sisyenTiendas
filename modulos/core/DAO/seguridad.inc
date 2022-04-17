<?php

function disableModule($linkModule){
    global $ModuleLink;
    $_SESSION['ModulesEnabled'][array_search($linkModule,$ModuleLink)]=0;

};
function enableModule($linkModule){
    global $ModuleLink;
    $_SESSION['ModulesEnabled'][array_search($linkModule,$ModuleLink)]=1;

};