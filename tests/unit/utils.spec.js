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

describe('Utility "nextString".', () => {
  it('returns the next string in alphabet order', () => {
    expect(Utils.nextString(undefined)).toEqual('A')
    expect(Utils.nextString(null)).toEqual('A')
    expect(Utils.nextString('a')).toEqual('b')
    expect(Utils.nextString('a')).toEqual('b')
    expect(Utils.nextString('Z')).toEqual('a')
    expect(Utils.nextString('z')).toEqual('zA')
    expect(Utils.nextString('abc')).toEqual('abd')
    expect(Utils.nextString('ZA')).toEqual('ZB')
  })
})
