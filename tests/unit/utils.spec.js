import * as Utils from '@/utils'

describe('Utility "capitalize".', () => {
  it('capitalize each word in a string.', () => {
    expect(Utils.capitalize('toto TITI tutu')).toEqual('Toto TITI Tutu')
  })
})

describe('Utility "expandIds".', () => {
  it('expandIds "1-3+5+9" -> 1,2,3,5,9', () => {
    expect(Utils.expandIds('1')).toEqual([1])
    expect(Utils.expandIds('1+3')).toEqual([1, 3])
    expect(Utils.expandIds('1-3+5+9')).toEqual([1, 2, 3, 5, 9])
  })
})

describe('Utility "groupBy".', () => {
  it('groupBy items in array by their given property value', () => {
    expect(Utils.groupBy(['one', 'two', 'three'], 'length')).toEqual({3: ['one', 'two'], 5: ['three']})
  })
})

describe('Utility "replaceAll".', () => {
  it('replace all occurences of a substring in a string', () => {
    expect(Utils.replaceAll('toto', 'o', 'in')).toEqual('tintin')
  })
})
