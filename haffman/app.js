var nameFile = process.argv[2];
var fs = require('fs');
var text = fs.readFileSync(nameFile, 'utf8');
var arrayText = text.split('');
var NoRepeat = unique(arrayText);
var weight = {};
var symbol = '';

NoRepeat.reverse();
//получаем вес символов
for(var x = 0; x < NoRepeat.length; x++){
    symbol = NoRepeat[x];
    weight[symbol] = substr_count(arrayText, NoRepeat[x]);
}
//fs.writeFileSync('weight.txt', JSON.stringify(weight));
HuffmanEncode(weight);
//console.log(Object.keys(weight)[0]);


//возвращает число повторений
function substr_count( haystack, needle, offset, length ) {

    var cnt = 0;

    if(isNaN(offset)) offset = 0;
    if(isNaN(length)) length = 0;
    offset--;

    while( (offset = haystack.indexOf(needle, offset+1)) != -1 ){
        if(length > 0 && (offset+needle.length) > length){
            return false;
        } else{
            cnt++;
        }
    }
    return cnt;
}


//удаляет повторы из массива
function unique(arr) {
    var obj = {};

    for (var i = 0; i < arr.length; i++) {
        var str = arr[i];
        obj[str] = true; // запомнить строку в виде свойства объекта
    }

    return Object.keys(obj); // или собрать ключи перебором для IE8-
}

function HuffmanEncode(obj) {
    var flag = 0;
    var Buff = {};
    for (var key in obj){
        Buff[key] = '';
    }
    while(Object.keys(weight).length != 1){

    }
    console.log(Buff);
}