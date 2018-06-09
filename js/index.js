/********es6 随机数, 使用了eslint, 所以计算时括号要明确*********/
const initX = 100
const initY = 500
// (Math.random() * (maxWidth - 20 + 1) | 0) + 20
const rule = (initX - initY) + 1
const leftRand = (Math.random() * rule) + initY
/********es6 随机数, 使用了eslint, 所以计算时括号要明确*********/

/*****截取并替换电话号码*****/
var replacePhone = function (phone) {
  var replaceStr = phone.substring(3, 7)
  var result = phone.replace(replaceStr, '****')
  return result
}
/*****截取并替换电话号码*****/
