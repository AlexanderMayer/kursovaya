<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import axios from 'axios';
import { useRoute, useRouter } from 'vue-router';
import Cookies from "js-cookie";

const router = useRouter();
const route = useRoute();
let lot = ref([]);
let name = ref(null);
let surname = ref(null);
let new_cost = ref(null);
let timers = ref([]);
let currentImageIndex = ref(0);
let isModalOpen = ref(false);
let isLoading = ref(false);
let isCostUpdating = ref(false);
let userId = ref(null);
let sellerId = ref(null);
let delFlag = ref(false);

const data = async () => {
    try {
        const lot_id = route.params.id;
        const token = Cookies.get('token');
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

        name.value = lot.value.seller.name;
        surname.value = lot.value.seller.surname;
        sellerId.value = lot.value.seller.id;

        const responseUserId = await axios.post('http://localhost/kurs2.2/public/api/user/edit', {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        userId.value = responseUserId.data.data.id;

        if(userId.value === sellerId.value){
            delFlag.value = true;
        }

    } catch (error) {
        console.error('Ошибка вывода', error);
        throw error;
    }
};

async function betUp() {
    try {
        isLoading.value = true;
        isCostUpdating.value = true;
        const lot_id = route.params.id;
        const token = Cookies.get('token');

        await axios.post(`http://localhost/kurs2.2/public/api/lots/${lot_id}/bet`, {
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
    } finally {
        isLoading.value = false;
        setTimeout(() => {
            isCostUpdating.value = false;
        }, 500);
    }
}

async function betStepUp(bet_up) {
    try {
        isLoading.value = true;
        isCostUpdating.value = true;
        const lot_id = route.params.id;
        const token = Cookies.get('token');

        await axios.post(`http://localhost/kurs2.2/public/api/lots/${lot_id}/bet`, {
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
    } finally {
        isLoading.value = false;
        setTimeout(() => {
            isCostUpdating.value = false;
        }, 500);
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
        timers.value.forEach(timer => {
            timer.remainingTime = Math.max(timer.remainingTime - 1000, 0);
        });
    }, 1000);

    onBeforeUnmount(() => {
        clearInterval(intervalId);
    });
}

function openModal(index) {
    currentImageIndex.value = index;
    isModalOpen.value = true;
}

function closeModal() {
    isModalOpen.value = false;
}

function nextImage() {
    currentImageIndex.value = (currentImageIndex.value + 1) % lot.value.images.length;
}

function previousImage() {
    currentImageIndex.value =
        (currentImageIndex.value - 1 + lot.value.images.length) % lot.value.images.length;
}

async function deleteLot() {
    try {
        const lot_id = route.params.id;
        const token = Cookies.get('token');
        await axios.delete(`http://localhost/kurs2.2/public/api/lots/${lot_id}`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        router.push({ name: 'start' });
    } catch (error) {
        console.error('Ошибка удаления лота', error);
        throw error;
    }
}

onMounted(() => {
    data();
    startTimer();
});
</script>

<template>
    <h1>Лот номер: {{ lot.id }}</h1>
    <div class="container" v-show="lot" :class="{ 'loading': isLoading }">
        <div class="row">
            <div class="col-md-6">
                <div id="carouselExample" class="carousel slide" >
                    <div class="carousel-inner">
                        <div v-for="(photo, index) in lot.images" :key="index" class="carousel-item" :class="{ active: index === currentImageIndex }">
                            <div class="zoom-container">
                                <img class="d-block w-100 lot-image" :src="`../storage/${photo}`" alt="Фото лота" @click="openModal(index)"/>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" @click="previousImage">
                        <span class="carousel-control-prev-icon"></span>
                        <span class="visually-hidden">Предыдущий</span>
                    </button>
                    <button class="carousel-control-next" type="button" @click="nextImage">
                        <span class="carousel-control-next-icon"></span>
                        <span class="visually-hidden">Следующий</span>
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <h2>{{ lot.title }}</h2>
                <p>{{ lot.description }}</p>
                <p>Продавец: {{ name }} {{ surname }}</p>
                <p>{{ formatTime(timers.find(timer => timer.id === lot.id)?.remainingTime) }}</p>
                <div>
                    <p v-if="lot.cost != 0" :class="{ 'cost-updating': isCostUpdating }">Текущая цена: {{ lot.cost }}</p>
                    <p v-else :class="{ 'cost-updating': isCostUpdating }">Текущая цена: {{ lot.start_cost }}</p>
                    <input type="text" v-model="new_cost" class="form-control w-50 my-2" placeholder="Введите вашу ставку">
                    <button class="btn btn-dark" @click.prevent="betUp">Сделать ставку</button>
                    <p>Шаг ставки: {{ lot.bet_step }}</p>
                    <button class="btn btn-dark" @click.prevent="betStepUp(lot.bet_step)">Увеличить ставку на шаг</button>
                </div>
                <button v-if="delFlag" class="btn btn-danger mt-3" @click="deleteLot">Удалить лот </button>
            </div>
        </div>

        <div v-if="isModalOpen" class="modal show d-block" tabindex="-1">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" @click="closeModal"></button>
                    </div>
                    <div class="modal-body d-flex justify-content-center align-items-center">
                        <img :src="`../storage/${lot.images[currentImageIndex]}`" class="img-fluid" alt="Фото лота">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" @click="previousImage">Предыдущая</button>
                        <button class="btn btn-secondary" @click="nextImage">Следующая</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.lot-image {
    cursor: zoom-in;
    transition: transform 0.3s ease;
}

.lot-image:hover {
    transform: scale(1.20);
}

.zoom-container {
    overflow: hidden;
}

.modal-body img {
    max-height: 90vh;
    object-fit: contain;
}

.loading {
    cursor: wait;
}

.cost-updating {
    transition: transform 0.5s ease, opacity 0.5s ease;
    transform: scale(1.05);
    opacity: 0.7;
}

.cost-updating:not(.cost-updating) {
    transform: scale(1);
    opacity: 1;
}
</style>
