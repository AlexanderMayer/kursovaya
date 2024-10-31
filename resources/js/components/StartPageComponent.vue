<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import Cookies from "js-cookie";
import {thisUrl} from "../api.js";

const router = useRouter();
let allLots = ref([]);
let displayedLots = ref([]);
let timers = ref([]);
let currentPage = ref(1);
const itemsPerPage = 9;

const Data = async () => {
    try {
        const response = await axios.get(`${thisUrl()}/main`);
        allLots.value = response.data.activeLots;
        updateDisplayedLots();
        timers.value = allLots.value.map(lot => ({ id: lot.id, remainingTime: calculateRemainingTime(lot.created_at) }));
    } catch (error) {
        console.error('Ошибка вывода', error);
    }
};

const updateDisplayedLots = () => {
    const start = (currentPage.value - 1) * itemsPerPage;
    displayedLots.value = allLots.value.slice(start, start + itemsPerPage);
};

function showLot(id) {
    const token = Cookies.get('token');
    if (!token) {
        router.push({ name: 'login' });
    } else {
        router.push({
            name: 'show',
            params: { id: id }
        });
    }
}

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
        for (let i = 0; i < timers.value.length; i++) {
            timers.value[i].remainingTime = Math.max(timers.value[i].remainingTime - 1000, 0);
        }
    }, 1000);

    onBeforeUnmount(() => {
        clearInterval(intervalId);
    });
}


function nextPage() {
    if (currentPage.value * itemsPerPage < allLots.value.length) {
        currentPage.value++;
        updateDisplayedLots();
        window.scrollTo(0, 0);
    }
}

function prevPage() {
    if (currentPage.value > 1) {
        currentPage.value--;
        updateDisplayedLots();
        window.scrollTo(0, 0);
    }
}

onMounted(() => {
    Data();
    startTimer();
});
</script>

<template>
    <div class="container">
        <h2 class="my-4">Активные лоты</h2>
        <div class="row row-cols-1 row-cols-md-3 g-4" v-show="displayedLots.length">
            <div class="col" v-for="lot in displayedLots" :key="lot.id">
                <div class="card h-100 lot-card" @click.prevent="showLot(lot.id)">
                    <img v-if="lot.photos.length > 0" :src="`./storage/${lot.photos[0].adress}`" class="card-img-top lot-image" alt="Фото лота"/>
                    <div v-else class="card-img-top bg-light d-flex justify-content-center align-items-center lot-image">
                        Нет фотографий
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ lot.title }}</h5>
                        <p class="card-text">{{ lot.description }}</p>
                        <p class="card-text">
                            <small class="text-muted">Продавец: {{ lot.seller.name + ' ' + lot.seller.surname }}</small>
                        </p>
                        <p class="card-text">{{ formatTime(timers.find(timer => timer.id === lot.id)?.remainingTime) }}</p>
                    </div>
                </div>
            </div>
        </div>
        <p v-if="!displayedLots.length" class="text-center">Нет активных лотов для отображения</p>

        <div class="d-flex justify-content-between my-4">
            <button class="btn btn-secondary" @click="prevPage" :disabled="currentPage === 1">Назад</button>
            <button class="btn btn-secondary" @click="nextPage" :disabled="(currentPage * itemsPerPage) >= allLots.length">Вперед</button>
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
