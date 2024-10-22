<script setup>
import {ref, onMounted} from 'vue';
import axios from 'axios';
import {useRoute} from 'vue-router';

const router = useRoute();
let lot = ref([]);

const data = async () => {
    try {
        const lot_id = router.params.id
        const response = await axios.get('http://localhost/kurs2.2/public/api/lots/45');
        lot.value = response.data;
        console.log(response.data);
    } catch (error) {
        console.error('Ошибка вывода', error);
        throw error;
    }
}

onMounted(() => {
    data();
});

</script>

<template>
    <h1>Лот номер: {{lot.id}}</h1>
    <div class="container" v-show="lot">
        <div class="row">
            <div class="col" v-if="lot.images">
                <div v-for="photo in lot.images">
                    <img class="rounded float-start w-75 h-75" :src="`./storage/${photo.adress}`" alt="Фото лота">
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
<!--            <div class="col">-->
<!--                Продавец: {{ lot.seller.name + ' ' + lot.seller.surname }}-->
<!--            </div>-->
<!--            <div class="col">-->
<!--                {{ formatTime(timers.find(timer => timer.id === lot.id)?.remainingTime) }}-->
<!--            </div>-->
        </div>
    </div>
</template>

<style scoped>

</style>
