// Sleep function (in millisecond)
// e.g. await sleep(1000)
export const sleep = (ms) => new Promise(resolve => setTimeout(resolve, ms))

// Capitalize
export const capitalize = (str) => str.replace(/\b\w/g, l => l.toUpperCase())

// Expand a list of nodes containing dash intervals
// "1-3+5+9" -> 1,2,3,5,9
export const expandIds = function (factExp) {
  factExp = factExp.split('+')
  var exp, dashExpression
  exp = []
  for (var i = 0; i < factExp.length; i++) {
    dashExpression = factExp[i].split('-')
    if (dashExpression.length === 2) {
      for (var j = parseInt(dashExpression[0]); j < (parseInt(dashExpression[1]) + 1); j++) {
        exp.push(j)
      }
    } else exp.push(parseInt(factExp[i]))
  }
  exp.sort((a, b) => a - b) // sort numerically and ascending
  for (i = 1; i < exp.length; i++) {
    if (exp[i] === exp[i - 1]) {
      exp.splice(i--, 1)
    }
  }
  return exp
}

// Group an array of items by item.key
// groupBy(['one', 'two', 'three'], 'length'))
// => {3: ["one", "two"], 5: ["three"]}
export const groupBy = function (array, key) {
  return array.reduce(function (rv, x) {
    (rv[x[key]] = rv[x[key]] || []).push(x)
    return rv
  }, {})
}

// Replace all occurences of a substring in a string
export const replaceAll = function (str, search, replacement) {
  // could be done with a RegExp paying attention to escape chars, but this solution is not bad in performance
  return str.split(search).join(replacement)
}

// Download JSON object as file in the browser
export const downloadObjectAsJson = function (exportObj, exportName) {
  var dataStr = 'data:text/json;charset=utf-8,' + encodeURIComponent(JSON.stringify(exportObj))
  var downloadAnchorNode = document.createElement('a')
  downloadAnchorNode.setAttribute('href', dataStr)
  downloadAnchorNode.setAttribute('download', exportName + '.json')
  downloadAnchorNode.click()
  downloadAnchorNode.remove()
}
