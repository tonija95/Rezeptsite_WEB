# Personal Recipe Book

A full-stack web application that serves as a **digital personal cookbook**.  
Users can create, edit, and organize their own recipes with images, search and filter by ingredients and categories, rate recipes, and manage favorites or a shopping list.  
Admins have additional rights to manage users and moderate recipes.

---

## 🎯 Purpose
This app helps hobby cooks organize and store recipes in one place, while discovering new inspiration.  
Features like favorites and a shared shopping list make it easy to plan meals and manage ingredients efficiently.

---

## 👥 Target Group
Hobby cooks who want to collect, organize, and share their own recipes digitally.

---

## 🧩 Key Technologies
- **Frontend:** HTML, CSS (Bootstrap)
- **Backend:** PHP (no frameworks)
- **Database:** MariaDB (via XAMPP)
- **File Uploads:** Recipe images (JPG, PNG)


---

## 📁 Folder Overview
| Folder | Description |
|--------|-------------|
| `public/` | Web root, contains all visible pages (index, login, dashboard, etc.) |
| `includes/` | Common PHP includes (header, footer, db connection) |
| `config/` | Configuration files (e.g. DB credentials) |
| `db/` | SQL scripts for database setup and example data |
| `uploads/` | Stores uploaded recipe images |
| `README.md` | Main project description |

---

## ⚙️ Local Setup
1. Place this folder inside your XAMPP directory:  
   `C:\xampp\htdocs\rezeptsite`
2. Start **Apache** (and later **MySQL**) in XAMPP.
3. Open your browser at:  
   [http://localhost/rezeptsite/public/](http://localhost/rezeptsite/public/)
4. Edit code in **VS Code**.
5. Use **Git + GitHub** for version control.

---

## 👤 User Roles
- **Guest:** Can browse recipes, filter and view details.
- **User:** Can add/edit/delete own recipes, upload images, mark favorites, and manage a shopping list.
- **Admin:** Can manage users and delete or lock recipes.

---

## 📂 Database Entities (planned)
- `users` – registered users with roles (user/admin)
- `recipes` – main recipe data (title, category, time, image, ingredients, instructions)
- `favorites` – link between users and recipes
- `shopping_list` – ingredients added from recipes
