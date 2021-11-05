import {expect, it} from '@jest/globals';


it('is configured correctly', () => {
    const element = document.createElement('div');
    expect(element).not.toBeNull();
    expect(global).not.toBeNull()
    expect(globalThis).not.toBeNull()
    expect(global.fetch).not.toBeNull()
    expect(globalThis.fetch).not.toBeNull()
});
