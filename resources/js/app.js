require('./bootstrap');
import './libs/trix';

import Tagify from '@yaireo/tagify'
import Alpine from 'alpinejs';
import sort from '@alpinejs/sort'

import radioPlayer from "./store/modules/radio-player";

Alpine.store('radioPlayer', radioPlayer)

Alpine.plugin(sort)

setTimeout(() => {
    Alpine.start();
}, 300);

window.Alpine = Alpine;
window.Tagify = Tagify;

Livewire.on('notify', payload => {
    window.dispatchEvent(new CustomEvent('notify', {detail: payload}));
})
