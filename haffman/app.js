var nameFile = process.argv[2];
var fs = require('fs');
var text = fs.readFileSync(nameFile, 'utf8');
var arrayText = text.split('');
var NoRepeat = unique(arrayText);
var weight = {};
var symbol = '';
var max = 0;
var arrayTrue = [];
var arrayFalse = [];


//NoRepeat.reverse();

//получаем вес символов
for(var x = 0; x < NoRepeat.length; x++){
    symbol = NoRepeat[x];
    weight[symbol] = substr_count(arrayText, NoRepeat[x]);
}
//fs.writeFileSync('weight.txt', JSON.stringify(weight));
HuffmanEncode(weight, NoRepeat);
//min(weight);
// console.log(weight);


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

//Вызаваем цикл записи в дерево
function HuffmanEncode(obj) {
    var flag = 0;
    var tables = {};
    console.log(obj);
    while(Object.keys(obj).length != 1){
        min(obj);
    }
}

//построение дерева
function min(obj){
    var minName = "";
    var minOne = obj[Object.keys(obj)[0]];
    var flag = 0;
    for (var name in obj) {
        if (minOne > obj[name]) {
            minOne = obj[name];
            minName = name;
            flag++;
        }
        if(flag == 0){
            flag++;
            minName = name;
        }
    }
    var argOne = minName;
    delete obj[minName];
    arrayTrue.push(argOne);

    flag = 0;
    var minTwo = obj[Object.keys(obj)[0]];
    for (var name in obj) {
        if (minTwo > obj[name]) {
            minTwo = obj[name];
            minName = name;
            flag++;
        }
        if(flag == 0){
            flag++;
            minName = name;
        }
    }
    var argTwo = minName;
    delete obj[argTwo];
    arrayFalse.push(argTwo);


    obj[argOne + argTwo] = minOne+minTwo;

    console.log(arrayTrue);
    console.log(arrayFalse);
    console.log(obj);
}