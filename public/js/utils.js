const API_CONFIG = {
    baseUrl: 'https://postulaciones.amplifica.io/',
    endpoints: {
        auth: 'auth',
        regions: 'regionalConfig',
        rate: 'getRate',
    }
};

async function apiRequest(endpoint, options = {}) {
    const token = localStorage.getItem('jwt_token');
    if (!token && endpoint !== 'auth') {
        window.location.href = '/';
        return;
    }

    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...(token && { 'Authorization': `Bearer ${token}` }),
        ...options.headers
    };

    try {
        const response = await fetch(`/api/${endpoint}`, {
            ...options,
            headers
        });

        const data = await response.json();
        
        if (response.status === 200) {
            return data;
        }
        
        throw new Error(data.error || 'API request failed');
    } catch (error) {
        console.error(`Error in ${endpoint}:`, error);
        throw error;
    }
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('es-CL', {
        style: 'currency',
        currency: 'CLP'
    }).format(amount);
}

function calculateTotals(products) {
    return {
        items: products.reduce((sum, product) => sum + product.quantity, 0),
        weight: products.reduce((sum, product) => sum + (product.weight * product.quantity), 0),
        price: products.reduce((sum, product) => sum + (product.price * product.quantity), 0)
    };
}

function createSelectOptions(container, data, valueKey, textKey, optgroupKey = null) {
    container.innerHTML = '';
    
    if (optgroupKey) {
        data.forEach(group => {
            const optgroup = document.createElement('optgroup');
            optgroup.label = group[optgroupKey];
            
            group.comunas.forEach(comuna => {
                const option = document.createElement('option');
                option.value = comuna;
                option.textContent = `  ${comuna}`;
                optgroup.appendChild(option);
            });
            
            container.appendChild(optgroup);
        });
    } else {
        data.forEach(item => {
            const option = document.createElement('option');
            option.value = item[valueKey];
            option.textContent = item[textKey];
            container.appendChild(option);
        });
    }
}

function createElement(tag, attributes = {}, children = []) {
    const element = document.createElement(tag);
    
    Object.entries(attributes).forEach(([key, value]) => {
        if (key === 'className') {
            element.className = value;
        } else {
            element.setAttribute(key, value);
        }
    });
    
    children.forEach(child => {
        if (typeof child === 'string') {
            element.textContent = child;
        } else {
            element.appendChild(child);
        }
    });
    
    return element;
} 