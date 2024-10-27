import './bootstrap';
import {createApp} from 'vue';
import router from './router';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';

import '../sass/app.scss';

import MainComponent from './components/MainComponent.vue';


const app = createApp({});
app.use(router);


app.component('main-component', MainComponent);


app.mount('#app');
