<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import Vue3Select from 'vue3-select';
import 'vue3-select/dist/vue3-select.css';
import Cookies from "js-cookie";
import {thisUrl} from "../../api.js";

const router = useRouter();
let categories = ref([]);
let title = ref('');
let description = ref('');
let start_cost = ref('');
let bet_step = ref('');
let category_id = ref(null);
let photos = ref([]);
const searchQuery = ref('');
let showSuccessModal = ref(false);
let showErrorModal = ref(false);
let errorMessages = ref([]);

const data = async () => {
    try {
        const responseCategories = await axios.get(`${thisUrl()}/main`);
        categories.value = responseCategories.data.cats;
    } catch (error) {
        console.error('Ошибка вывода', error);
        throw error;
    }
};

const validateForm = () => {
    errorMessages.value = [];
    let isValid = true;

    if (!title.value) {
        errorMessages.value.push('Название лота обязательно для заполнения.');
        isValid = false;
    } else if (title.value.length > 254) {
        errorMessages.value.push('Название лота не должно превышать 254 символа.');
        isValid = false;
    }

    if (!description.value) {
        errorMessages.value.push('Описание обязательно для заполнения.');
        isValid = false;
    } else if (description.value.length > 254) {
        errorMessages.value.push('Описание не должно превышать 254 символа.');
        isValid = false;
    }

    if (!start_cost.value) {
        errorMessages.value.push('Начальная стоимость обязательна для заполнения.');
        isValid = false;
    }

    if (!bet_step.value) {
        errorMessages.value.push('Шаг ставки обязателен для заполнения.');
        isValid = false;
    }

    if (category_id.value === null) {
        errorMessages.value.push('Категория лота обязательна для выбора.');
        isValid = false;
    }

    if (photos.value.length === 0) {
        errorMessages.value.push('Необходимо добавить хотя бы одно фото.');
        isValid = false;
    }

    return isValid;
};

const createLot = async () => {
    if (!validateForm()) {
        showErrorModal.value = true;
        return;
    }

    try {
        const token = Cookies.get('token');

        const formData = new FormData();
        formData.append('title', title.value);
        formData.append('description', description.value);
        formData.append('start_cost', start_cost.value);
        formData.append('bet_step', bet_step.value);
        formData.append('category_id', category_id.value);
        photos.value.forEach(photo => {
            formData.append('photos[]', photo);
        });

        const response = await axios.post(`${thisUrl()}/lots`, formData, {
            headers: {
                Authorization: `Bearer ${token}`,
            }
        });

        showSuccessModal.value = true;
        clearForm();
        setTimeout(() => {
            showSuccessModal.value = false;
        }, 3000);
    } catch (error) {
        console.error('Ошибка создания', error);
        throw error;
    }
};

const clearForm = () => {
    title.value = '';
    description.value = '';
    start_cost.value = '';
    bet_step.value = '';
    category_id.value = null;
    photos.value = [];
    searchQuery.value = '';
};

const filteredCategories = computed(() => {
    if (!searchQuery.value) return categories.value;
    return categories.value.filter(category =>
        category.category_name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

onMounted(() => {
    data();
    const token = Cookies.get('token');
    if (!token) {
        router.push({ name: 'login' });
    }
});
</script>

<template>
    <div class="container mt-4">

        <div class="w-50 mx-auto">
            <h3>Добавление лота</h3>
            <input type="text" v-model="title" class="form-control my-3" placeholder="Название лота" maxlength="254">
            <textarea v-model="description" class="form-control my-3" placeholder="Описание" maxlength="254"></textarea>
            <input type="number" v-model="start_cost" class="form-control my-3" placeholder="Начальная стоимость">
            <input type="number" v-model="bet_step" class="form-control my-3" placeholder="Шаг ставки">
            <Vue3Select v-model="category_id" :options="filteredCategories" label="category_name" placeholder="Поиск и выбор категории" @search="val => searchQuery = val" :reduce="category => category.id" />
            <input type="file" @change="event => photos = Array.from(event.target.files)" multiple class="form-control my-3" placeholder="Фотографии">
            <button @click.prevent="createLot" class="btn btn-success w-100 mb-2">Выставить лот</button>
        </div>

        <div v-if="showErrorModal" class="alert alert-danger">
            <ul>
                <li v-for="(error, index) in errorMessages" :key="index">{{ error }}</li>
            </ul>
        </div>

        <div v-if="showSuccessModal" class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Успех</h5>
                    </div>
                    <div class="modal-body">
                        <p>Ваш лот добавлен!</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<style scoped>
.modal.fade.show.d-block {
    opacity: 1;
    transition: opacity 1s ease-in-out;
}
</style>
