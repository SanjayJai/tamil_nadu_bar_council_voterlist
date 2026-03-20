Project overview

This workspace contains two main parts:

- Backend: located in the bakend/ folder. The backend is a Laravel PHP application.
- Frontend: located in the frontend/ folder. The frontend is a JavaScript/React application.

Live links

- Frontend (live): https://bctnpy.selvalegal.com/
- Backend (live): https://barcouncil.selvalegal.com/

Notes

- A `.gitignore` file is present to exclude `node_modules`, `vendor`, build outputs, IDE files, OS artifacts, and archive files such as `*.zip`.
- To run locally, open the `frontend/` and `bakendnew/` folders and follow their individual README instructions.

Quick local start (examples):

Frontend (from workspace root):

```
cd frontend
npm install
npm run dev
```

Backend (from workspace root):

```
cd bakend
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```
