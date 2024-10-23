<script setup>
import {ref, onMounted} from 'vue';
import axios from 'axios';
import {useRoute} from 'vue-router';
import {useRouter} from 'vue-router';

const router = useRouter();
const route = useRoute();
let lot = ref([]);
let name = ref(null);
let surname = ref(null);
let new_cost = ref(null);
let bet_up = ref(null);

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

onMounted(() => {
    data();
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
            <!--            <div class="col">-->
            <!--                {{ formatTime(timers.find(timer => timer.id === lot.id)?.remainingTime) }}-->
            <!--            </div>-->
        </div>
        <div class="row">
            <p>Текущая цена: {{ lot.cost }}</p>
            <!--            добавить вывод стартовой цены, если есть-->
            <input type="text" v-model="new_cost" class="form-control w-25">
            <button class=" btn btn-dark" @click.prevent="betUp">Сделать ставку</button>
            <p>Шаг ставки: {{ lot.bet_step }}</p>
            <button class=" btn btn-dark" @click.prevent="betStepUp(lot.bet_step)">Увеличить ставку на шаг</button>
        </div>
    </div>
</template>

<style scoped>

</style>
