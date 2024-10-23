<script setup>
import {ref, onMounted, onBeforeUnmount} from 'vue';
import axios from 'axios';
import {useRouter} from 'vue-router';

const router = useRouter();
let lots = ref([]);
let timers = ref([]);

const data = async () => {
    try {
        const response = await axios.get('api/main');
        lots.value = response.data;
        timers.value = lots.value.map(lot => ({id: lot.id, remainingTime: calculateRemainingTime(lot.created_at)}));
    } catch (error) {
        console.error('Ошибка вывода', error);
        throw error;
    }
}

function showLot(id) {
    const token = document.cookie.split('; ').find(row => row.startsWith('token='));
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

onMounted(() => {
    data();
    startTimer();
});
</script>

<template>
    <div>Здесь будут отображаться лоты стартовой страницы</div>
    <div class="container" v-show="lots.length">
        <div class="row" v-for="lot in lots" :key="lot.id" @click.prevent="showLot(lot.id)">
            <div class="col" v-if="lot.photos.length > 0">
                <img class="rounded float-start w-75 h-75" :src="`./storage/${lot.photos[0].adress}`" alt="Фото лота">
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
                Продавец: {{ lot.seller.name + ' ' + lot.seller.surname }}
            </div>
            <div class="col">
                {{ formatTime(timers.find(timer => timer.id === lot.id)?.remainingTime) }}
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
