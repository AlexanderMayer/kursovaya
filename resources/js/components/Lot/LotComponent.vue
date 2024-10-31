<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import axios from 'axios';
import { useRoute, useRouter } from 'vue-router';
import Cookies from "js-cookie";
import {thisUrl} from "../../api.js";

const router = useRouter();
const route = useRoute();
let lot = ref([]);
let name = ref(null);
let surname = ref(null);
let new_cost = ref(null);
let timers = ref([]);
let currentImageIndex = ref(0);
let isModalOpen = ref(false);
let isValidationModalOpen = ref(false);
let validationMessage = ref("");
let isLoading = ref(false);
let isCostUpdating = ref(false);
let user = ref([]);
let sellerId = ref(null);
let delFlag = ref(false);

const data = async () => {
    try {
        const lotId = route.params.id;
        const token = Cookies.get('token');
        const response = await axios.get(`${thisUrl()}/lots/${lotId}`, {
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
        if(lot.value.seller){
            name.value = lot.value.seller.name;
            surname.value = lot.value.seller.surname;
            sellerId.value = lot.value.seller.id;
        }

        const responseUser = await axios.post(`${thisUrl()}/user/edit`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        user.value = responseUser.data.data;

        if(user.value.id === sellerId.value){
            delFlag.value = true;
        }
    } catch (error) {
        console.error('Ошибка вывода', error);
        throw error;
    }
};

async function betUp() {
    try {
        const minBet = lot.value.bet_step;
        if (new_cost.value < minBet) {
            validationMessage.value = `Ставка должна быть не менее ${minBet}!`;
            isValidationModalOpen.value = true;
            return;
        }

        isLoading.value = true;
        isCostUpdating.value = true;
        const lot_id = route.params.id;
        const token = Cookies.get('token');

        await axios.post(`${thisUrl()}/lots/${lot_id}/bet`, {
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

        await axios.post(`${thisUrl()}/lots/${lot_id}/bet`, {
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

function closeValidationModal() {
    isValidationModalOpen.value = false;
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
        const lotId = route.params.id;
        const token = Cookies.get('token');
        await axios.delete(`${thisUrl()}/lots/${lotId}`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        await router.push({ name: 'start' });
    } catch (error) {
        console.error('Ошибка удаления лота', error);
        throw error;
    }
}

async function showSeller(sellerId) {
    try {
        const token = Cookies.get('token');
        await axios.post(`${thisUrl()}/user/${sellerId}`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        await router.push({
            name: 'user.show.seller',
            params: { id:sellerId }
        });
    } catch (error) {
        console.error('Ошибка просмотра продаыца', error);
        throw error;
    }
}

onMounted(() => {
    data();
    startTimer();
});
</script>

<template>
    <div class="container mt-4" v-show="lot" :class="{ 'loading': isLoading }">
        <div class="row">
            <div class="col-md-6">
                <div id="carouselExample" class="carousel slide" style="height: 400px;">
                    <div class="carousel-inner h-100">
                        <div v-for="(photo, index) in lot.images" :key="index"
                             class="carousel-item"
                             :class="{ active: index === currentImageIndex }">
                            <div class="d-flex justify-content-center align-items-center h-100">
                                <img class="d-block lot-image"
                                     :src="`../storage/${photo}`"
                                     alt="Фото лота"
                                     style=" object-fit: fill;"
                                     @click="openModal(index)" />
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev custom-carousel-control" type="button" @click="previousImage">
                        <span class="carousel-control-prev-icon"></span>
                        <span class="visually-hidden">Предыдущий</span>
                    </button>
                    <button class="carousel-control-next custom-carousel-control" type="button" @click="nextImage">
                        <span class="carousel-control-next-icon"></span>
                        <span class="visually-hidden">Следующий</span>
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h2 class="card-title">{{ lot.title }}</h2>
                            <p class="card-text">{{ lot.description }}</p>
                            <p @click.prevent="showSeller(sellerId)" v-if="(name !== null) && (surname !== null)" class="btn btn-sm btn-outline-secondary">Продавец: {{ name }} {{ surname }}</p>
                        </div>
                        <div v-if="lot.status === 'active'">
                            <p>{{ formatTime(timers.find(timer => timer.id === lot.id)?.remainingTime) }}</p>
                            <p v-if="lot.cost !== 0" class="current-cost" :class="{ 'cost-updating': isCostUpdating }">
                                Текущая цена: {{ lot.cost }}
                            </p>
                            <p v-else class="current-cost" :class="{ 'cost-updating': isCostUpdating }">
                                Текущая цена: {{ lot.start_cost }}
                            </p>
                            <div class="mb-3" v-if="!delFlag">
                                <input type="text" v-model="new_cost" class="form-control my-2" placeholder="Введите вашу ставку">
                                <button class="btn btn-dark w-100" @click.prevent="betUp">Сделать ставку</button>
                                <p class="mt-2">Шаг ставки: {{ lot.bet_step }}</p>
                                <button class="btn btn-secondary w-100" @click.prevent="betStepUp(lot.bet_step)">Увеличить ставку на шаг</button>
                            </div>
                        </div>
                        <button v-if="delFlag || (user.role === 2)" class="btn btn-danger mt-3" @click="deleteLot">Удалить лот</button>
                    </div>
                </div>
            </div>
        </div>


        <div v-if="isValidationModalOpen" class="modal show d-block" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ошибка ставки</h5>
                        <button type="button" class="btn-close" @click="closeValidationModal"></button>
                    </div>
                    <div class="modal-body">
                        <p>{{ validationMessage }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeValidationModal">Закрыть</button>
                    </div>
                </div>
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
    width: 400px;
}

.lot-image:hover {
    transform: scale(1.15);
}

.current-cost {
    font-size: 1.5rem;
    font-weight: bold;
    transition: transform 0.5s ease, opacity 0.5s ease;
}

.cost-updating {
    transform: translateY(-10px);
    opacity: 0.8;
}

.loading {
    cursor: wait;
}

.modal-body img {
    max-height: 90vh;
    object-fit: contain;
}

.custom-carousel-control .carousel-control-prev-icon,
.custom-carousel-control .carousel-control-next-icon {
    background-color: black;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-size: 75%;
    background-position: center;
}

.custom-carousel-control {
    filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.5));
}
</style>
