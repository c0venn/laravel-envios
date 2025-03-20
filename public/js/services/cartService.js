class CartService {
    constructor() {
        this.products = [
            {
                id: 1,
                name: "Producto 1",
                price: 2999,
                quantity: 1,
                weight: 1
            },
            {
                id: 2,
                name: "Producto 2",
                price: 3999,
                quantity: 1,
                weight: 2
            },
            {
                id: 3,
                name: "Producto 3",
                price: 4999,
                quantity: 1,
                weight: 3
            }
        ];
        this.shippingRate = 0;
    }

    async loadRegions() {
        try {
            const data = await apiRequest('regions');
            const regionSelect = document.getElementById('region');
            createSelectOptions(regionSelect, data, 'code', 'region', 'region');
        } catch (error) {
            console.error('Error loading regions:', error);
        }
    }

    renderCart() {
        const cartContainer = document.getElementById('cart-items');
        cartContainer.innerHTML = '';
        
        this.products.forEach(product => {
            const item = createElement('div', {
                className: 'd-flex justify-content-between align-items-center mb-3'
            }, [
                createElement('div', {}, [
                    createElement('h5', {}, [product.name]),
                    createElement('p', { className: 'mb-0' }, [formatCurrency(product.price)]),
                    createElement('small', { className: 'text-muted' }, [`Peso: ${product.weight}g`])
                ]),
                createElement('div', { className: 'input-group', style: 'width: 150px;' }, [
                    createElement('button', {
                        className: 'btn btn-outline-secondary',
                        onclick: `cartService.updateQuantity(${product.id}, -1)`
                    }, ['-']),
                    createElement('input', {
                        type: 'number',
                        className: 'form-control text-center',
                        value: product.quantity,
                        onchange: `cartService.updateQuantity(${product.id}, 0, this.value)`
                    }),
                    createElement('button', {
                        className: 'btn btn-outline-secondary',
                        onclick: `cartService.updateQuantity(${product.id}, 1)`
                    }, ['+'])
                ])
            ]);
            cartContainer.appendChild(item);
        });
        
        this.updateSummary();
        this.calculateShippingRate();
    }

    updateQuantity(productId, change, newValue = null) {
        const product = this.products.find(p => p.id === productId);
        if (product) {
            if (newValue !== null) {
                product.quantity = parseInt(newValue) || 0;
            } else {
                product.quantity = Math.max(0, product.quantity + change);
            }
            this.renderCart();
        }
    }

    async calculateShippingRate() {
        const region = document.getElementById('region').value;
        if (!region) {
            document.getElementById('shipping-rate').textContent = formatCurrency(0);
            this.updateSummary();
            return;
        }

        try {
            const result = await apiRequest('getRate', {
                method: 'POST',
                body: JSON.stringify({
                    comuna: region,
                    products: this.products
                        .filter(product => product.quantity > 0)
                        .map(product => ({
                            quantity: product.quantity,
                            weight: product.weight
                        }))
                })
            });

            const sortedRates = result.sort((a, b) => a.price - b.price);
            const rateSelect = createElement('select', {
                id: 'shipping-rate-select',
                className: 'form-select'
            });

            sortedRates.forEach(rate => {
                const option = createElement('option', {
                    value: rate.price
                }, [`${rate.name} - ${formatCurrency(rate.price)} (${rate.transitDays} días)`]);
                rateSelect.appendChild(option);
            });

            const shippingRateElement = document.getElementById('shipping-rate');
            shippingRateElement.innerHTML = '';
            shippingRateElement.appendChild(rateSelect);

            this.shippingRate = sortedRates[0]?.price || 0;
            rateSelect.addEventListener('change', (e) => {
                this.shippingRate = Number(e.target.value);
                this.updateSummary();
            });
            
            this.updateSummary();
        } catch (error) {
            console.error('Error calculating shipping rate:', error);
            document.getElementById('shipping-rate').textContent = formatCurrency(0);
        }
    }

    updateSummary() {
        const totals = calculateTotals(this.products);
        
        document.getElementById('total-items').textContent = totals.items;
        document.getElementById('total-weight').textContent = totals.weight;
        document.getElementById('total-price').textContent = formatCurrency(totals.price + this.shippingRate);
    }

    async submitOrder() {
        const region = document.getElementById('region').value;
        if (!region) {
            alert('Please select a region');
            return;
        }

        try {
            const orderData = {
                items: this.products.map(product => ({
                    id: product.id,
                    quantity: product.quantity,
                    weight: product.weight
                })),
                totalWeight: calculateTotals(this.products).weight / 1000,
                shippingRate: this.shippingRate,
                region: region
            };

            await apiRequest('order', {
                method: 'POST',
                body: JSON.stringify(orderData)
            });

            alert('Order submitted successfully!');
            this.products.forEach(product => product.quantity = 0);
            this.renderCart();
        } catch (error) {
            alert('Error submitting order: ' + error.message);
        }
    }
}

const cartService = new CartService(); 