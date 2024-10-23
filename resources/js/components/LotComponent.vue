<script setup>
import {ref, onMounted, onBeforeUnmount} from 'vue';
import axios from 'axios';
import {useRoute} from 'vue-router';
import {useRouter} from 'vue-router';

const router = useRouter();
const route = useRoute();
let lot = ref([]);
let name = ref(null);
let surname = ref(null);
let new_cost = ref(null);
let timers = ref([]);

const data = async () => {
    try {
        const lot_id = route.params.id
        const token = document.cookie.split('; ').find(row => row.startsWith('token=')).split('=')[1];
        const response = await axios.get(`http://localhost/kurs2.2/public/api/lots/${lot_id}`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        lot.value = response.data;
        const currentTime = new Date();
        timers.value = [{
            id: lot.value.id,
            remainingTime: calculateRemainingTime(lot.value.created_at, currentTime)
        }];
        name = lot.value.seller.name;
        surname = lot.value.seller.surname;
    } catch (error) {
        console.error('Ошибка вывода', error);
        throw error;
    }
}

async function betUp() {
    try {
        const lot_id = route.params.id
        const token = document.cookie.split('; ').find(row => row.startsWith('token=')).split('=')[1];

        const response = await axios.post(`http://localhost/kurs2.2/public/api/lots/${lot_id}/bet`, {
            new_cost: new_cost.value,
            bet_up: 0,
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        await data();
        new_cost.value = null;
    } catch (error) {
        console.error('Ошибка ставки', error);
        throw error;
    }
}

async function betStepUp(bet_up) {
    try {
        const lot_id = route.params.id
        const token = document.cookie.split('; ').find(row => row.startsWith('token=')).split('=')[1];

        const response = await axios.post(`http://localhost/kurs2.2/public/api/lots/${lot_id}/bet`, {
            new_cost: 0,
            bet_up: bet_up,
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        await data();
    } catch (error) {
        console.error('Ошибка ставки', error);
        throw error;
    }
}

router.beforeEach((to, from, next) => {
    const token = document.cookie.split('; ').find(row => row.startsWith('token='));

    if (to.name !== 'login' && !token) {
        next({name: 'login'});
    } else {
        next();
    }
});

function calculateRemainingTime(createdAt) {
    const createdDate = new Date(createdAt);
    const endDate = new Date(createdDate.getTime() + 3 * 24 * 60 * 60 * 1000);
    return Math.max(endDate - new Date(), 0);
}

function formatTime(remainingTime) {
    if (remainingTime === 0) {
        return 'Аукцион завершен';
    }

    const days = Math.floor(remainingTime % (1000 * 60 * 60 * 24 * 365) / (1000 * 60 * 60 * 24));
    const hours = Math.floor((remainingTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);

    return `${days}д ${hours}ч ${minutes}м ${seconds}с`;
}

function startTimer() {
    const intervalId = setInterval(() => {
        timers.value.forEach(timer => {
            timer.remainingTime = Math.max(timer.remainingTime - 1000, 0);
        });
    }, 1000);

    onBeforeUnmount(() => {
        clearInterval(intervalId);
    });
}

onMounted(() => {
    data();
    startTimer();
});

</script>

<template>
    <h1>Лот номер: {{ lot.id }}</h1>
    <div class="container" v-show="lot">
        <div class="row">
            <div class="col" v-if="lot.images">
                <div v-for="photo in lot.images">
                    <img class="rounded float-start w-75 h-75" :src="`../storage/${photo}`" alt="Фото лота">
                </div>
            </div>
            <div class="col" v-else>
                Нет фотографий
            </div>
            <div class="col">
                {{ lot.title }}
            </div>
            <div class="col">
                {{ lot.description }}
            </div>
            <div class="col">
                Продавец: {{ name + ' ' + surname }}
            </div>
            <div class="col">
                {{ formatTime(timers.find(timer => timer.id === lot.id)?.remainingTime) }}
            </div>
        </div>
        <div class="row">
            <p v-if="lot.cost != 0">Текущая цена: {{ lot.cost }}</p>
            <p v-else>Текущая цена: {{ lot.start_cost }}</p>
            <input type="text" v-model="new_cost" class="form-control w-25">
            <button class=" btn btn-dark" @click.prevent="betUp">Сделать ставку</button>
            <p>Шаг ставки: {{ lot.bet_step }}</p>
            <button class=" btn btn-dark" @click.prevent="betStepUp(lot.bet_step)">Увеличить ставку на шаг</button>
        </div>
    </div>
</template>

<style scoped>

</style>
