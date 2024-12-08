<script setup>
import {ref, onMounted, onBeforeUnmount,computed} from 'vue';
import axios from 'axios';
import {useRouter} from 'vue-router';
import Vue3Select from 'vue3-select';
import 'vue3-select/dist/vue3-select.css';
import Cookies from "js-cookie";
import {thisUrl} from "../../api.js";

const router = useRouter();
let lots = ref([]);
let user = ref([]);
let timers = ref([]);
let categories = ref([]);
let category_id = ref('');
const searchQuery = ref('');
let currentStatus = ref('');
const statuses = ref([
    {id: 'active', name: 'Активные лоты'},
    {id: 'inactive', name: 'Не активные лоты'},
    {id: 'sold', name: 'Проданные лоты'},
]);

const data = async () => {
    try {
        const response = await axios.post(`${thisUrl()}/lots/my`);
        lots.value = response.data;

        const responseCategories = await axios.get(`${thisUrl()}/main`);
        categories.value = responseCategories.data.cats;

        const token = Cookies.get('token');
        const responseUser = await axios.post(`${thisUrl()}/user/edit`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        user.value = responseUser.data.data;

        timers.value = lots.value.map(lot => ({id: lot.id, remainingTime: calculateRemainingTime(lot.created_at)}));
    } catch (error) {
        console.error('Ошибка вывода лотов', error);
        throw error;
    }
}

function showLot(id) {
    const token = Cookies.get('token');
    if (!token) {
        router.push({name: 'login'});
    } else {
        router.push({
            name: 'show',
            params: {id: id}
        });
    }
}

function calculateRemainingTime(createdAt) {
    const createdDate = new Date(createdAt);
    const endDate = new Date(createdDate.getTime() + 3 * 24 * 60 * 60 * 1000);
    return Math.max(endDate - new Date(), 0);
}

function formatTime(remainingTime) {
    const days = Math.floor(remainingTime % (1000 * 60 * 60 * 24 * 365) / (1000 * 60 * 60 * 24));
    const hours = Math.floor((remainingTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);

    return `${days}д ${hours}ч ${minutes}м ${seconds}с`;
}

function startTimer() {
    const intervalId = setInterval(() => {
        for (let i = 0; i < timers.value.length; i++) {
            timers.value[i].remainingTime = Math.max(timers.value[i].remainingTime - 1000, 0);
        }
    }, 1000);

    onBeforeUnmount(() => {
        clearInterval(intervalId);
    });
}

const showLots = async (category_id, currentStatus) => {
    try {
        const response = await axios.post(`${thisUrl()}/lots/my`, {
            category_id: category_id,
            status_id: currentStatus,
        });
        const responseCategories = await axios.get(`${thisUrl()}/main`);
        categories.value = responseCategories.data.cats;
        lots.value = response.data;
        timers.value = lots.value.map(lot => ({id: lot.id, remainingTime: calculateRemainingTime(lot.created_at)}));
    } catch (error) {
        console.error('Ошибка вывода лотов', error);
        throw error;
    }
}

const showAllLots = async () => {
    try {
        const response = await axios.post(`${thisUrl()}/lots/my`);
        const responseCategories = await axios.get(`${thisUrl()}/main`);
        categories.value = responseCategories.data.cats;
        lots.value = response.data;
        timers.value = lots.value.map(lot => ({id: lot.id, remainingTime: calculateRemainingTime(lot.created_at)}));
    } catch (error) {
        console.error('Ошибка вывода лотов', error);
        throw error;
    }
}

const filteredCategories = computed(() => {
    if (!searchQuery.value) return categories.value;
    return categories.value.filter(category =>
        category.category_name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const filteredStatuses = computed(() => {
    if (!searchQuery.value) return statuses.value;
    return statuses.value.filter(status =>
        status.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

onMounted(() => {
    data();
    startTimer();
    const token = Cookies.get('token');
    if (!token) {
        router.push({ name: 'login' });
    }
});

</script>

<template>
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-6">
                <Vue3Select v-model="category_id" :options="filteredCategories" label="category_name" placeholder="Поиск и выбор категории" @search="val => searchQuery = val"/>
            </div>
            <div class="col-md-6">
                <Vue3Select v-model="currentStatus" :options="filteredStatuses" label="name" placeholder="Поиск и выбор статуса" @search="val => searchQuery = val"/>
            </div>
        </div>
        <div class="mb-4">
            <button @click.prevent="showLots(category_id,currentStatus)" class="btn btn-success me-3">
                Найти
            </button>
            <button @click.prevent="showAllLots" class="btn btn-secondary">
                Показать все лоты
            </button>
        </div>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <div class="col" v-for="lot in lots" :key="lot.id">
                <div class="card h-100 lot-card" @click.prevent="showLot(lot.id)">
                    <img v-if="lot.images.length > 0" :src="`../../storage/${lot.images[0].adress}`" class="card-img-top lot-image" alt="Фото лота"/>
                    <div v-else class="card-img-top bg-light d-flex justify-content-center align-items-center lot-image">
                        Нет фотографий
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ lot.title }}</h5>
                        <p class="card-text">{{ lot.description }}</p>
                        <p class="card-text">
                            <small class="text-muted">Продавец: {{ user.name + ' ' + user.surname }}</small>
                        </p>
                        <p class="card-text" v-show="lot.status === 'active'">{{ formatTime(timers.find(timer => timer.id === lot.id)?.remainingTime) }}</p>
                        <span v-if="lot.status === 'active'" class="badge bg-success">Активный</span>
                        <span v-else-if="lot.status === 'inactive'" class="badge bg-warning text-dark">Не активный</span>
                        <span v-else class="badge bg-danger">Продан</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.lot-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
}
.lot-card:hover {
    transform: scale(1.10);
    box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.2);
}
.lot-image {
    height: 200px;
    width: 100%;
    object-fit: cover;
}

.container {
    max-width: 1820px;
}
</style>

