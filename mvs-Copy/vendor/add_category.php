<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Category</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    .error-message {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .success-message {
        color: #10b981;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .category-card {
        transition: all 0.3s ease;
    }

    .category-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    </style>
</head>

<body class="bg-gray-100">
    <!-- Header/Navigation -->
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800">Admin Dashboard</h1>
            <nav>
                <ul class="flex space-x-6">
                    <li><a href="#" class="text-gray-600 hover:text-blue-600">Dashboard</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-blue-600">Products</a></li>
                    <li><a href="#" class="text-blue-600 font-medium">Categories</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-blue-600">Orders</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Add New Category</h1>
                <a href="#" id="backButton" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Categories
                </a>
            </div>

            <div id="successMessage"
                class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded hidden">
                Category added successfully!
            </div>

            <form id="categoryForm" class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Category Name *</label>
                    <input type="text" id="name" name="name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
                    <p id="nameError" class="error-message hidden">Category name is required</p>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" name="description" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"></textarea>
                    <p id="descriptionError" class="error-message hidden">Description must be less than 500 characters
                    </p>
                </div>

                <div class="flex justify-end space-x-4">
                    <button type="button" id="cancelButton"
                        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Add Category
                    </button>
                </div>
            </form>
        </div>

        <!-- Display existing categories (simulated data) -->
        <div class="mt-12">
            <h2 class="text-xl font-semibold mb-4">Existing Categories</h2>
            <div id="categoriesList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Categories will be added here dynamically -->
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t mt-12 py-6">
        <div class="container mx-auto px-4 text-center text-gray-500">
            <p>© 2023 My Store. All rights reserved.</p>
        </div>
    </footer>

    <script>
    // Simulated database
    let categories = [{
            id: 1,
            name: "Electronics",
            description: "Devices and gadgets"
        },
        {
            id: 2,
            name: "Clothing",
            description: "Apparel and accessories"
        },
        {
            id: 3,
            name: "Home & Garden",
            description: "Furniture and decor"
        }
    ];

    // DOM elements
    const form = document.getElementById('categoryForm');
    const nameInput = document.getElementById('name');
    const descriptionInput = document.getElementById('description');
    const nameError = document.getElementById('nameError');
    const descriptionError = document.getElementById('descriptionError');
    const successMessage = document.getElementById('successMessage');
    const categoriesList = document.getElementById('categoriesList');
    const backButton = document.getElementById('backButton');
    const cancelButton = document.getElementById('cancelButton');

    // Display existing categories
    function displayCategories() {
        categoriesList.innerHTML = '';
        categories.forEach(category => {
            const categoryCard = document.createElement('div');
            categoryCard.className = 'category-card bg-white p-4 rounded-lg shadow border border-gray-200';
            categoryCard.innerHTML = `
                    <h3 class="font-semibold text-lg">${category.name}</h3>
                    ${category.description ? `<p class="text-gray-600 mt-1">${category.description}</p>` : ''}
                    <div class="mt-3 flex justify-end space-x-2">
                        <button class="text-blue-600 hover:text-blue-800 edit-btn" data-id="${category.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="text-red-600 hover:text-red-800 delete-btn" data-id="${category.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
            categoriesList.appendChild(categoryCard);
        });

        // Add event listeners to edit buttons
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = parseInt(this.getAttribute('data-id'));
                editCategory(id);
            });
        });

        // Add event listeners to delete buttons
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = parseInt(this.getAttribute('data-id'));
                deleteCategory(id);
            });
        });
    }

    // Form validation
    function validateForm() {
        let isValid = true;

        // Validate name
        if (!nameInput.value.trim()) {
            nameError.textContent = 'Category name is required';
            nameError.classList.remove('hidden');
            nameInput.classList.add('border-red-500');
            isValid = false;
        } else if (nameInput.value.trim().length > 100) {
            nameError.textContent = 'Category name must be less than 100 characters';
            nameError.classList.remove('hidden');
            nameInput.classList.add('border-red-500');
            isValid = false;
        } else {
            nameError.classList.add('hidden');
            nameInput.classList.remove('border-red-500');
        }

        // Validate description
        if (descriptionInput.value.trim().length > 500) {
            descriptionError.classList.remove('hidden');
            descriptionInput.classList.add('border-red-500');
            isValid = false;
        } else {
            descriptionError.classList.add('hidden');
            descriptionInput.classList.remove('border-red-500');
        }

        return isValid;
    }

    // Add new category
    function addCategory(name, description) {
        // In a real app, this would be an API call
        const newCategory = {
            id: categories.length > 0 ? Math.max(...categories.map(c => c.id)) + 1 : 1,
            name: name.trim(),
            description: description.trim()
        };

        categories.push(newCategory);
        displayCategories();

        // Show success message
        successMessage.classList.remove('hidden');
        setTimeout(() => {
            successMessage.classList.add('hidden');
        }, 3000);

        // Reset form
        form.reset();
    }

    // Edit category
    function editCategory(id) {
        const category = categories.find(c => c.id === id);
        if (category) {
            nameInput.value = category.name;
            descriptionInput.value = category.description || '';

            // Change form to edit mode
            form.dataset.editId = id;
            form.querySelector('button[type="submit"]').textContent = 'Update Category';

            // Scroll to form
            form.scrollIntoView({
                behavior: 'smooth'
            });
        }
    }

    // Delete category
    function deleteCategory(id) {
        if (confirm('Are you sure you want to delete this category?')) {
            categories = categories.filter(c => c.id !== id);
            displayCategories();
        }
    }

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        if (validateForm()) {
            const name = nameInput.value;
            const description = descriptionInput.value;

            if (this.dataset.editId) {
                // Update existing category
                const id = parseInt(this.dataset.editId);
                const index = categories.findIndex(c => c.id === id);
                if (index !== -1) {
                    categories[index] = {
                        ...categories[index],
                        name: name.trim(),
                        description: description.trim()
                    };
                    displayCategories();
                    successMessage.textContent = 'Category updated successfully!';
                    successMessage.classList.remove('hidden');
                    setTimeout(() => {
                        successMessage.classList.add('hidden');
                    }, 3000);

                    // Reset form
                    delete this.dataset.editId;
                    this.reset();
                    this.querySelector('button[type="submit"]').textContent = 'Add Category';
                }
            } else {
                // Add new category
                addCategory(name, description);
            }
        }
    });

    // Input validation on typing
    nameInput.addEventListener('input', function() {
        if (this.value.trim()) {
            nameError.classList.add('hidden');
            this.classList.remove('border-red-500');
        }
    });

    descriptionInput.addEventListener('input', function() {
        if (this.value.trim().length <= 500) {
            descriptionError.classList.add('hidden');
            this.classList.remove('border-red-500');
        }
    });

    // Button events
    backButton.addEventListener('click', function(e) {
        e.preventDefault();
        alert('In a real app, this would take you back to the categories list');
    });

    cancelButton.addEventListener('click', function() {
        form.reset();
        if (form.dataset.editId) {
            delete form.dataset.editId;
            form.querySelector('button[type="submit"]').textContent = 'Add Category';
        }
        nameError.classList.add('hidden');
        descriptionError.classList.add('hidden');
        nameInput.classList.remove('border-red-500');
        descriptionInput.classList.remove('border-red-500');
    });

    // Initialize the page
    displayCategories();
    </script>
</body>

</html>