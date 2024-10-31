<script setup>
import { ref } from 'vue';
import axios from 'axios';
import Cookies from "js-cookie";
import { thisUrl } from "../../api.js";
import {useRoute} from "vue-router";

let complaint = ref('');
const route = useRoute();
let showSuccessModal = ref(false);
let showErrorModal = ref(false);
let errorMessages = ref([]);

const validateForm = () => {
    errorMessages.value = [];
    let isValid = true;

    if (!complaint.value) {
        errorMessages.value.push('Пожалуйста, введите текст жалобы.');
        isValid = false;
    } else if (complaint.value.length > 250) {
        errorMessages.value.push('Жалоба не должна превышать 250 символов.');
        isValid = false;
    }
    return isValid;
};

const submitComplaint = async () => {
    if (!validateForm()) {
        showErrorModal.value = true;
        return;
    }
    try {
        const token = Cookies.get('token');
        const sellerId = route.params.id;

        const response = await axios.post(`${thisUrl()}/user/${sellerId}/complaint`, { content: complaint.value }, {
            headers: {
                Authorization: `Bearer ${token}`,
            }
        });

        showSuccessModal.value = true;
        complaint.value = '';
        setTimeout(() => {
            showSuccessModal.value = false;
        }, 3000);
    } catch (error) {
        console.error('Ошибка отправки жалобы', error);
        showErrorModal.value = true;
    }
};
</script>

<template>
    <div class="container mt-4">
        <div class="w-50 mx-auto">
            <h3>Подать жалобу на продавца</h3>
            <textarea v-model="complaint" class="form-control my-3" placeholder="Введите текст жалобы или ссылку на лот продавыца" maxlength="500"></textarea>
            <button @click.prevent="submitComplaint" class="btn btn-danger w-100">Отправить жалобу</button>
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
                        <p>Жалоба отправлена!</p>
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
