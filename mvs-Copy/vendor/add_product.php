<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product | Vendor Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    .image-preview-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 1rem;
    }

    .image-preview {
        height: 120px;
        border: 2px dashed #d1d5db;
        border-radius: 0.5rem;
        position: relative;
    }

    .image-preview img {
        object-fit: cover;
        width: 100%;
        height: 100%;
        border-radius: 0.5rem;
    }

    .remove-image {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #ef4444;
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    #imageUpload {
        opacity: 0;
        position: absolute;
        z-index: -1;
    }
    </style>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-6 max-w-6xl">
        <div class="bg-white rounded-xl shadow-md p-6">
            <!-- Header -->
            <a href="vendor.php"
                class="group flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-300">
                <!-- Animated Arrow -->
                <div class="relative w-8 h-8 mr-2">
                    <div
                        class="absolute inset-0 flex items-center justify-center transition-all duration-500 group-hover:-translate-x-1">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </div>
                    <div
                        class="absolute inset-0 flex items-center justify-center opacity-0 transition-all duration-500 group-hover:opacity-100 group-hover:translate-x-1">
                        <i class="fas fa-sign-in-alt text-xl"></i>
                    </div>
                </div>
                <!-- Text -->
                <span class="font-medium text-lg transition-all duration-300 group-hover:translate-x-1">
                    Back to Dashboard
                </span>
            </a>

            <!-- Progress Steps -->
            <div class="mb-8">
                <div class="flex items-center">
                    <div class="flex items-center text-blue-600 relative">
                        <div class="rounded-full h-8 w-8 bg-blue-600 flex items-center justify-center text-white">
                            1
                        </div>
                        <div class="absolute top-0 -ml-10 text-center mt-10 w-32 text-xs font-medium text-blue-600">
                            Basic Info</div>
                    </div>
                    <div class="flex-auto border-t-2 border-blue-600"></div>
                    <div class="flex items-center text-gray-500 relative">
                        <div class="rounded-full h-8 w-8 bg-gray-300 flex items-center justify-center">
                            2
                        </div>
                        <div class="absolute top-0 -ml-10 text-center mt-10 w-32 text-xs font-medium">Pricing</div>
                    </div>
                    <div class="flex-auto border-t-2 border-gray-300"></div>
                    <div class="flex items-center text-gray-500 relative">
                        <div class="rounded-full h-8 w-8 bg-gray-300 flex items-center justify-center">
                            3
                        </div>
                        <div class="absolute top-0 -ml-10 text-center mt-10 w-32 text-xs font-medium">Inventory</div>
                    </div>
                    <div class="flex-auto border-t-2 border-gray-300"></div>
                    <div class="flex items-center text-gray-500 relative">
                        <div class="rounded-full h-8 w-8 bg-gray-300 flex items-center justify-center">
                            4
                        </div>
                        <div class="absolute top-0 -ml-10 text-center mt-10 w-32 text-xs font-medium">Media</div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form id="addProductForm" class="space-y-6">
                <!-- Basic Information Section -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800">Basic Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Product Name -->
                        <div>
                            <label for="productName" class="block text-sm font-medium text-gray-700 mb-1">Product Name
                                *</label>
                            <input type="text" id="productName" name="productName" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category
                                *</label>
                            <select id="category" name="category" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Category</option>
                                <option value="electronics">Electronics</option>
                                <option value="fashion">Fashion</option>
                                <option value="home-garden">Home & Garden</option>
                                <option value="beauty">Beauty</option>
                                <option value="sports">Sports</option>
                                <option value="toys">Toys</option>
                            </select>
                        </div>

                        <!-- Brand -->
                        <div>
                            <label for="brand" class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                            <input type="text" id="brand" name="brand"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Subcategory -->
                        <div>
                            <label for="subcategory"
                                class="block text-sm font-medium text-gray-700 mb-1">Subcategory</label>
                            <input type="text" id="subcategory" name="subcategory"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description
                                *</label>
                            <textarea id="description" name="description" rows="4" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Pricing Section -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800">Pricing</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price *</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">$</span>
                                </div>
                                <input type="number" id="price" name="price" step="0.01" min="0" required
                                    class="block w-full pl-7 pr-12 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="0.00">
                            </div>
                        </div>

                        <!-- Compare Price -->
                        <div>
                            <label for="comparePrice" class="block text-sm font-medium text-gray-700 mb-1">Compare at
                                Price</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">$</span>
                                </div>
                                <input type="number" id="comparePrice" name="comparePrice" step="0.01" min="0"
                                    class="block w-full pl-7 pr-12 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="0.00">
                            </div>
                        </div>

                        <!-- Cost Per Item -->
                        <div>
                            <label for="costPerItem" class="block text-sm font-medium text-gray-700 mb-1">Cost Per
                                Item</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">$</span>
                                </div>
                                <input type="number" id="costPerItem" name="costPerItem" step="0.01" min="0"
                                    class="block w-full pl-7 pr-12 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="0.00">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inventory Section -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800">Inventory</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- SKU -->
                        <div>
                            <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">SKU (Stock Keeping
                                Unit)</label>
                            <input type="text" id="sku" name="sku"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Barcode -->
                        <div>
                            <label for="barcode" class="block text-sm font-medium text-gray-700 mb-1">Barcode (ISBN,
                                UPC, etc.)</label>
                            <input type="text" id="barcode" name="barcode"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Quantity -->
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity
                                *</label>
                            <input type="number" id="quantity" name="quantity" min="0" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Stock Status -->
                        <div>
                            <label for="stockStatus" class="block text-sm font-medium text-gray-700 mb-1">Stock
                                Status</label>
                            <select id="stockStatus" name="stockStatus"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                <option value="in_stock">In Stock</option>
                                <option value="out_of_stock">Out of Stock</option>
                                <option value="on_backorder">On Backorder</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Shipping Section -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800">Shipping</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Weight -->
                        <div>
                            <label for="weight" class="block text-sm font-medium text-gray-700 mb-1">Weight</label>
                            <div class="relative rounded-md shadow-sm">
                                <input type="number" id="weight" name="weight" step="0.01" min="0"
                                    class="block w-full pl-12 pr-12 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">kg</span>
                                </div>
                            </div>
                        </div>

                        <!-- Requires Shipping -->
                        <div class="flex items-center">
                            <input id="requiresShipping" name="requiresShipping" type="checkbox" checked
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="requiresShipping" class="ml-2 block text-sm text-gray-700">
                                This product requires shipping
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Variants Section -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800">Variants</h2>
                    <div class="space-y-4">
                        <!-- Color Variants -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Colors</label>
                            <div class="flex flex-wrap gap-2">
                                <div class="flex items-center">
                                    <input id="color-red" name="colors" type="checkbox" value="red"
                                        class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                    <label for="color-red" class="ml-2 text-sm text-gray-700">Red</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="color-blue" name="colors" type="checkbox" value="blue"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="color-blue" class="ml-2 text-sm text-gray-700">Blue</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="color-green" name="colors" type="checkbox" value="green"
                                        class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                    <label for="color-green" class="ml-2 text-sm text-gray-700">Green</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="color-black" name="colors" type="checkbox" value="black"
                                        class="h-4 w-4 text-gray-800 focus:ring-gray-500 border-gray-300 rounded">
                                    <label for="color-black" class="ml-2 text-sm text-gray-700">Black</label>
                                </div>
                            </div>
                        </div>

                        <!-- Size Variants -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sizes</label>
                            <div class="flex flex-wrap gap-2">
                                <div class="flex items-center">
                                    <input id="size-xs" name="sizes" type="checkbox" value="xs"
                                        class="h-4 w-4 text-gray-600 focus:ring-gray-500 border-gray-300 rounded">
                                    <label for="size-xs" class="ml-2 text-sm text-gray-700">XS</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="size-s" name="sizes" type="checkbox" value="s"
                                        class="h-4 w-4 text-gray-600 focus:ring-gray-500 border-gray-300 rounded">
                                    <label for="size-s" class="ml-2 text-sm text-gray-700">S</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="size-m" name="sizes" type="checkbox" value="m"
                                        class="h-4 w-4 text-gray-600 focus:ring-gray-500 border-gray-300 rounded">
                                    <label for="size-m" class="ml-2 text-sm text-gray-700">M</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="size-l" name="sizes" type="checkbox" value="l"
                                        class="h-4 w-4 text-gray-600 focus:ring-gray-500 border-gray-300 rounded">
                                    <label for="size-l" class="ml-2 text-sm text-gray-700">L</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="size-xl" name="sizes" type="checkbox" value="xl"
                                        class="h-4 w-4 text-gray-600 focus:ring-gray-500 border-gray-300 rounded">
                                    <label for="size-xl" class="ml-2 text-sm text-gray-700">XL</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Media Section -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800">Media</h2>
                    <div class="space-y-4">
                        <!-- Image Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Product Images *</label>
                            <div
                                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <div class="flex text-sm text-gray-600">
                                        <label for="imageUpload"
                                            class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                            <span>Upload images</span>
                                            <input id="imageUpload" name="images" type="file" multiple accept="image/*"
                                                class="sr-only">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        PNG, JPG, GIF up to 5MB
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Image Previews -->
                        <div class="image-preview-container hidden" id="imagePreviews">
                            <!-- Preview images will be added here by JavaScript -->
                        </div>
                    </div>
                </div>

                <!-- SEO Section -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800">SEO</h2>
                    <div class="space-y-4">
                        <!-- Meta Title -->
                        <div>
                            <label for="metaTitle" class="block text-sm font-medium text-gray-700 mb-1">Meta
                                Title</label>
                            <input type="text" id="metaTitle" name="metaTitle"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Meta Description -->
                        <div>
                            <label for="metaDescription" class="block text-sm font-medium text-gray-700 mb-1">Meta
                                Description</label>
                            <textarea id="metaDescription" name="metaDescription" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>

                        <!-- Product URL -->
                        <div>
                            <label for="productUrl" class="block text-sm font-medium text-gray-700 mb-1">Product
                                URL</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span
                                    class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    https://yourstore.com/products/
                                </span>
                                <input type="text" id="productUrl" name="productUrl"
                                    class="focus:ring-blue-500 focus:border-blue-500 flex-1 block w-full rounded-none rounded-r-md border-gray-300">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Section -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800">Status</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Product Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Product
                                Status</label>
                            <select id="status" name="status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                <option value="draft">Draft</option>
                                <option value="active" selected>Active</option>
                                <option value="archived">Archived</option>
                            </select>
                        </div>

                        <!-- Visibility -->
                        <div>
                            <label for="visibility"
                                class="block text-sm font-medium text-gray-700 mb-1">Visibility</label>
                            <select id="visibility" name="visibility"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                <option value="public" selected>Public</option>
                                <option value="hidden">Hidden</option>
                                <option value="private">Private</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6">
                    <button type="button"
                        class="px-6 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    // Image Upload Preview
    document.getElementById('imageUpload').addEventListener('change', function(e) {
        const previewContainer = document.getElementById('imagePreviews');
        previewContainer.innerHTML = '';

        if (this.files.length > 0) {
            previewContainer.classList.remove('hidden');

            for (let i = 0; i < this.files.length; i++) {
                const file = this.files[i];
                const reader = new FileReader();

                reader.onload = function(e) {
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'image-preview';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Preview';

                    const removeBtn = document.createElement('span');
                    removeBtn.className = 'remove-image';
                    removeBtn.innerHTML = '&times;';
                    removeBtn.onclick = function() {
                        previewDiv.remove();
                        if (previewContainer.children.length === 0) {
                            previewContainer.classList.add('hidden');
                        }
                    };

                    previewDiv.appendChild(img);
                    previewDiv.appendChild(removeBtn);
                    previewContainer.appendChild(previewDiv);
                }

                reader.readAsDataURL(file);
            }
        } else {
            previewContainer.classList.add('hidden');
        }
    });

    // Form Validation
    document.getElementById('addProductForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Basic validation
        const productName = document.getElementById('productName').value;
        const category = document.getElementById('category').value;
        const price = document.getElementById('price').value;
        const quantity = document.getElementById('quantity').value;
        const description = document.getElementById('description').value;

        if (!productName || !category || !price || !quantity || !description) {
            alert('Please fill in all required fields');
            return;
        }

        // Here you would normally send the form data to your backend
        console.log('Form submitted!');
        alert('Product saved successfully!');

        // For demo purposes, just show a success message
        // In a real app, you would submit to your backend here
    });
    </script>
</body>

</html>