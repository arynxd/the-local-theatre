import { render, unmountComponentAtNode } from "react-dom";
import { act } from "react-dom/test-utils";
import { BackendController } from '../src/backend/BackendController';
import PostElement from "../src/component/model/Post";
import "isomorphic-fetch" // polyfill fetch
import 'setimmediate' // polyfill for winston
import {expect, it, beforeEach, afterEach} from '@jest/globals';
import jest from 'jest-mock';
let container: HTMLDivElement | null = null;
const backend = new BackendController()

//TODO: look into storing mocks so that we can avoid ts-ignore
//TODO: clean this up
beforeEach(() => {
  // setup a DOM element as a render target
  container = document.createElement("div");
  document.body.appendChild(container);
  window.URL.createObjectURL = jest.fn();
});

afterEach(() => {
  // cleanup on exiting
  if (!container) {
    throw new TypeError('Container was not set')
  }
  unmountComponentAtNode(container);
  container.remove();
  container = null;
  // @ts-ignore
  window.URL.createObjectURL.mockReset();

});

it("renders post data", async () => {
  if (!container) {
    throw new TypeError('Container was not set')
  }

  const fakePost = {
    posts: [
      {
        id: 'EntityIdentifier',
        author: {
            id: '',
            name: 'string',
            permissions: 1,
            dob: 1,
            joinDate: 1,
            username: 'string'
        },
        title: 'string',
        content: 'string',
        createdAt: 1
      }
    ]
  };
  
  //@ts-ignore
  //FIXME: Make this dynamically resolve based on URL
  jest.spyOn(globalThis, "fetch").mockResolvedValue({
      json: () => Promise.resolve(fakePost),
      blob: () => Promise.resolve(new Blob()),
      text: () => Promise.resolve(JSON.stringify(fakePost)),
      ok: true,
      status: 200, 
    })

  // Use the asynchronous version of act to apply resolved promises
  await act(async () => {
    render(<PostElement postModel={fakePost.posts[0]} backend={backend} />, container);
  });

  expect(container.querySelector("h1")!!.textContent).toBe(fakePost.posts[0].title);
  // remove the mock to ensure tests are completely isolated
   //@ts-ignore
   globalThis.fetch.mockRestore();
});