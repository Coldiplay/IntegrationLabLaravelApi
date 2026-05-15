# Архитектура системы IntegrationLab и IntegrationLabLaravelApi

На основе анализа репозиториев, представлено подробное описание текущей архитектуры системы управления доставкой и маршрутизацией для водителей.

## 📋 Общее описание

Система состоит из трёх основных компонентов:

1. **Laravel 12 API** (`IntegrationLabLaravelApi`) - основной бэкенд для управления данными
2. **ASP.NET SignalR сервер** (`MobileSignalR`) - шлюз реального времени между API и мобильным приложением
3. **Avalonia кроссплатформенное приложение** (`IntegrationLab`) - мобильное ПО для водителей (Android/Desktop)

```
┌─────────────────────────────────────────────────────────────┐
│                    ИНТЕГРАЦИОННАЯ СИСТЕМА                    │
├─────────────────────────────────────────────────────────────┤
│                                                               │
│  ┌──────────────────┐    ┌──────────────────┐                │
│  │  Мобильное App   │    │   Веб-сайт       │                │
│  │  (Avalonia)      │    │   (Laravel Blade)│                │
│  │  Android/Desktop │    │                  │                │
│  └────────┬─────────┘    └────────┬─────────┘                │
│           │                       │                          │
│           └───────────┬───────────┘                          │
│                       │                                      │
│            ┌──────────▼──────────┐                           │
│            │  MobileSignalR      │                           │
│            │  ASP.NET (Hub)      │                           │
│            │  JWT Authentication │                           │
│            └──────────┬──────────┘                           │
│                       │                                      │
│            ┌──────────▼──────────┐                           │
│            │ Laravel 12 API      │                           │
│            │ RESTful Endpoints   │                           │
│            │ Sanctum Auth        │                           │
│            └──────────┬──────────┘                           │
│                       │                                      │
│            ┌──────────▼──────────┐                           │
│            │   MySQL Database    │                           │
│            │   (Eloquent ORM)    │                           │
│            └─────────────────────┘                           │
│                                                               │
└─────────────────────────────────────────────────────────────┘
```

---

## 🔌 API Laravel (IntegrationLabLaravelApi)

### Стек технологий
- **PHP 8.2+**
- **Laravel Framework 12.0**
- **Laravel Sanctum 4.3** - API токены и аутентификация
- **Moonshine 4.10** - админ-панель
- **Wadakatu Spectrum 1.1** - OpenAPI/Swagger документация

### Основные эндпоинты API

#### **Аутентификация** (без авторизации)
```
POST   /api/register              - Регистрация нового пользователя
POST   /api/login                 - Вход пользователя (возвращает token)
POST   /webhooks/sms/status       - Webhook для статусов SMS
```

#### **Пользователь** (protected)
```
POST   /api/logout                - Выход
GET    /api/user                  - Получить текущего пользователя
```

#### **Чаты** (protected) - `/api/chat`
```
GET    /api/chat                           - Список чатов пользователя
POST   /api/chat                           - Создать новый чат
GET    /api/chat/{chat}                    - Получить информацию о чате
PUT    /api/chat/{chat}                    - Обновить чат
DELETE /api/chat/{chat}                    - Удалить чат

GET    /api/chat/{chat}/members            - Список участников чата
POST   /api/chat/{chat}/addMember          - Добавить участника
DELETE /api/chat/{chat}/removeMember/{user} - Удалить участника

GET    /api/chat/{chat}/messages           - Получить сообщения чата
POST   /api/chat/{chat}/messages           - Отправить сообщение
```

#### **Сообщения** (protected) - `/api/message`
```
GET    /api/message/{message}              - Получить сообщение
PUT    /api/message/{message}              - Обновить сообщение
DELETE /api/message/{message}              - Удалить сообщение
```

#### **Доставки** (protected) - `/api/shipping`
```
GET    /api/shipping               - Список всех доставок
POST   /api/shipping               - Создать новую доставку
GET    /api/shipping/{shipping}    - Получить детали доставки
PUT    /api/shipping/{shipping}    - Обновить доставку
DELETE /api/shipping/{shipping}    - Удалить доставку
```

#### **Смены водителей** (protected) - `/api/shift`
```
GET    /api/shift                  - Список смен текущего водителя
PUT    /api/shift/{shipping}/start - Начать смену
GET    /api/shift/{shift}          - Информация о смене
PATCH  /api/shift/{shift}          - Завершить смену
DELETE /api/shift/{shift}          - Удалить смену
```

#### **Заказы доставки** (protected) - `/api/shipping-order`
```
GET    /api/shipping-order                 - Список заказов
POST   /api/shipping-order                 - Создать заказ
GET    /api/shipping-order/{shippingOrder} - Получить заказ
PUT    /api/shipping-order/{shippingOrder} - Обновить заказ
DELETE /api/shipping-order/{shippingOrder} - Удалить заказ
```

#### **Инциденты** (protected) - `/api/incident`
```
GET    /api/incident               - Список инцидентов
POST   /api/incident               - Зарегистрировать ��нцидент
GET    /api/incident/{incident}    - Детали инцидента
PUT    /api/incident/{incident}    - Обновить инцидент
DELETE /api/incident/{incident}    - Удалить инцидент
```

#### **Машины** (protected) - `/api/vehicle`
```
GET    /api/vehicle                - Список машин
POST   /api/vehicle                - Добавить машину
GET    /api/vehicle/{vehicle}      - Информация о машине
PUT    /api/vehicle/{vehicle}      - Обновить машину
DELETE /api/vehicle/{vehicle}      - Удалить машину
```

#### **Водители** (protected) - `/api/driver`
```
GET    /api/driver                 - Список водителей
POST   /api/driver                 - Добавить водителя
GET    /api/driver/{driver}        - Информация о водителе
PUT    /api/driver/{driver}        - Обновить водителя
DELETE /api/driver/{driver}        - Удалить водителя
```

---

## 🏗️ Архитектура базы данных Laravel API

### Основные модели и таблицы

```
users
├── id (PK)
├── name
├── email
├── login
├── password
├── email_verified_at
├── remember_token
├── created_at, updated_at
└── Relationships: messages, driver, chats (через ChatMember)

drivers
├── user_id (PK, FK → users.id)
├── Relationships: user, shippings, shifts, breaks

vehicles
├── id (PK)
├── vehicle_size (JSON - width, length, height)
├── body_size (JSON)
├── created_at, updated_at
└── Relationships: shippings, incidents, supportedCargoTypes

shippings
├── id (PK)
├── designated_driver_id (FK → drivers.user_id)
├── vehicle_id (FK → vehicles.id)
├── status (enum)
├── created_at, updated_at
└── Relationships: designatedDriver, vehicle, cargos

shipping_orders
├── id (PK)
├── shipping_id (FK → shippings.id)
├── created_at, updated_at

drivers_shifts
├── id (PK)
├── driver_id (FK → drivers.user_id)
├── shipping_id (FK)
├── start_time
├── end_time
└── Relationships: driver, breaks

shift_breaks
├── id (PK)
├── drivers_shift_id (FK → drivers_shifts.id)
├── start_time
├── end_time

incidents
├── id (PK)
├── driver_id (FK → users.id)
├── shipping_id (FK → shippings.id)
├── vehicle_id (FK → vehicles.id)
├── description
├── severity
├── status
└── created_at, updated_at

chats
├── id (PK)
├── name
├── created_at, updated_at
└── Relationships: members (через ChatMember)

chat_members
├── id (PK)
├── chat_id (FK → chats.id)
├── user_id (FK → users.id)
├── joined_at

messages
├── id (PK)
├── chat_id (FK → chats.id)
├── user_id (FK → users.id)
├── content
├── created_at, updated_at

notifications
├── id (PK)
├── user_id (FK → users.id)
├── title
├── message
├── read_at
├── created_at

notification_attempts
├── id (PK)
├── notification_id (FK → notifications.id)

cargoTypes
├── id (PK)
├── name

cargos
├── id (PK)
├── shipping_id (FK → shippings.id)
├── cargo_type_id (FK → cargoTypes.id)
├── dimensions (JSON)
├── weight

transportCargoTypes
├── id (PK)
├── vehicle_id (FK → vehicles.id)
├── cargo_type_id (FK → cargoTypes.id)
```

---

## 📡 SignalR сервер (MobileSignalR - ASP.NET)

### Стек технологий
- **.NET (последняя версия)**
- **ASP.NET Core SignalR** - для real-time коммуникации
- **JWT Bearer Authentication** - аутентификация с RSA ключами
- **Swagger/OpenAPI** - документация

### Архитектура SignalR Hub

**Основной Hub: `MobileHub`** (маппирован на `/hub`)

#### Методы Hub для мобильного приложения:

```csharp
// Получить участников чата
Task<Response> GetChatMembers(int chatId)

// Получить сообщения чата
Task<Response> GetChatMessages(int chatId)

// Получить все чаты пользователя
Task<Response> GetChats(int userId)

// Получить инциденты
Task<Response> GetIncidents(int userId)

// Получить доставки
Task<Response> GetShippings(int userId)

// Авторизация (анонимный метод)
// Отправляет credentials в Laravel API, получает токен
Task<Response> Authorize(string login, string password)
```

#### Способ работы:

1. **Мобильное приложение** отправляет `Authorize(login, password)` на SignalR Hub
2. **SignalR** пересылает запрос в **Laravel API** (`POST /api/login`)
3. **Laravel** проверяет credentials и возвращает **Laravel токен**
4. **SignalR** генерирует собственный **JWT токен** с RSA подписью и сохраняет маппинг
5. **Мобильное приложение** получает **SignalR JWT токен** и использует его для всех последующих запросов
6. Все остальные методы Hub транслируют запросы в **Laravel API**, используя **Laravel токен** из маппинга

```
Mobile App                SignalR Hub              Laravel API
    │                        │                         │
    ├──Authorize()──────────►│                         │
    │                        ├─POST /api/login────────►│
    │                        │                         │
    │                        │◄────Laravel Token───────┤
    │                        │                         │
    │◄──SignalR JWT Token────┤                         │
    │                        │                         │
    ├──GetShippings()───────►│                         │
    │                        ├─GET /api/shipping──────►│
    │                        │ (с Laravel Token)       │
    │                        │                         │
    │◄──Response────────────┤◄───Shippings Data───────┤
```

---

## 📱 Мобильное приложение Avalonia (IntegrationLab)

### Стек технологий
- **Avalonia UI** - кроссплатформенный XAML фреймворк
- **MVVM Community Toolkit** - паттерн MVVM
- **ASP.NET Core SignalR Client** - клиент для SignalR
- **Pomelo.EntityFrameworkCore.MySql** - доступ к БД
- **Target frameworks**: .NET 10.0, Android

### MVVM структура

Приложение использует **MVVM паттерн** с базовыми классами:

```
ViewModels/
├── ViewModelBase.cs           - базовый класс
├── ViewModelControlBase.cs    - base для контрол-вьюх
│
├── MainWindowViewModel.cs     - главное окно
├── MainViewModel.cs           - главное представление
│
├── ShippingsViewModel.cs      - список доставок
├── SingleShippingViewModel.cs - детали доставки
├── ActiveShippingViewModel.cs - активная доставка
│
├── ChatListViewModel.cs       - список чатов
├── ChatViewModel.cs           - открытый чат
│
├── IncidentsViewModel.cs      - список инцидентов
├── CreateIncidentViewModel.cs - создание инцидента
├── SingleIncidentViewModel.cs - детали инцидента
│
└── (Views синхронизируются через ViewLocator)
```

### Функционал мобильного приложения для водителя

#### 🚚 **Управление доставками**

**ShippingsViewModel** - основной экран водителя:
- Список активных доставок (статус: `ReadyToShip`, `Shipping`)
- Список завершённых доставок (статус: `Delivered`)
- Просмотр деталей конкретной доставки
- Подтверждение начала доставки

**Действия водителя:**
```
1. Просмотр назначенных доставок
2. Выбор доставки из списка (двойной клик)
3. Переход в SingleShippingView
4. Просмотр:
   - Назначенного водителя
   - Машины (характеристики)
   - Cargo (содержимое, размеры, вес)
   - Статуса доставки
5. Подтверждение ("Начать доставку")
6. Отслеживание активной доставки
```

#### 💬 **Система коммуникации (Чат)**

**ChatListViewModel** - список доступных чатов:
- Загрузка всех чатов пользователя через SignalR
- Список участников каждого чата
- Переход в отдельный чат

**ChatViewModel** - открытый чат:
- Отправка сообщений
- Получение истории сообщений
- Вывод участников чата
- Real-time обновления через SignalR

**Структура:**
```
Chat
├── id
├── name
└── members: User[]

Message
├── id
├── chat_id
├── user_id
├── content
├── created_at
```

#### ⚠️ **Управление инцидентами**

**IncidentsViewModel** - список инцидентов:
- Загрузка всех инцидентов водителя через SignalR
- Фильтрация по статусу
- Переход в детали инцидента

**CreateIncidentViewModel** - создание нового инцидента:
- Выбор типа инцидента
- Описание проблемы
- Указание машины и доставки
- Отправка в API

**Возможные инциденты:**
- ДТП
- Поломка машины
- Проблемы с грузом
- Задержка маршрута
- Невозможно доставить

#### 👤 **Данные водителя**

**Данные в приложении:**
```csharp
public static Driver CurrentDriver { get; set; }

public class Driver
{
    public ulong UserId { get; set; }
    public User User { get; set; }
    public Rights Rights { get; set; }  // A, B категории
}

public class User
{
    public ulong Id { get; set; }
    public string FirstName { get; set; }
    public string LastName { get; set; }
    public string Phone { get; set; }
    public string Login { get; set; }
}
```

#### 🔄 **Real-time синхронизация**

**HubData** - хранилище данных из Hub:
```csharp
public ObservableCollection<Shipping> Shippings { get; set; }
public ObservableCollection<Chat> Chats { get; set; }
public ObservableCollection<Incident> Incidents { get; set; }
```

**HubHandler** - управляет подключением и обновлениями:
- Подключение к SignalR Hub
- Синхронизация данных с сервером
- Обновление коллекций при изменениях

#### 🔐 **Аутентификация**

1. Водитель вводит логин и пароль
2. Отправляется `Authorize(login, password)` на SignalR Hub
3. Получается SignalR JWT токен
4. Все последующие запросы используют этот токен в заголовке `Authorization: Bearer {token}`
5. SignalR проверяет токен и преобразует его в Laravel токен для запросов к API

---

## 📊 Flow диаграммы

### Сценарий 1: Получение доставок водителем

```
Мобильное приложение          SignalR Hub              Laravel API
       │                          │                        │
       │─ GetShippings()─────────►│                        │
       │                          │─ GET /api/shipping────►│
       │                          │ (с Bearer токеном)     │
       │                          │                        │
       │                          │◄─ [Shipping{...}]──────│
       │◄─ Response[Shipping[]]───│                        │
       │                          │                        │
    (Обновить UI)
```

### Сценарий 2: Отправка сообщения в чат

```
Мобильное приложение          Laravel API
       │                          │
       │─ POST /api/chat/{id}────►│
       │   /messages              │
       │   {content: "..."}       │
       │                          │
       │◄─ Response {message}─────│
       │                          │
    (Добавить в список сообщений)
```

### Сценарий 3: Создание инцидента

```
Мобильное приложение          SignalR Hub              Laravel API
       │                          │                        │
       │─ CreateIncident()───────►│                        │
       │                          │─ POST /api/incident───►│
       │                          │ {driver_id, ...}      │
       │                          │                        │
       │                          │◄─ Response────────────│
       │◄─ Result────────────────│                        │
```

---

## 🛡️ Безопасность

### Аутентификация & Авторизация

**Laravel API:**
- **Laravel Sanctum** - API tokens
- Каждый пользователь имеет личные токены
- Токены хранятся в `personal_access_tokens` таблице
- Scope-based permissions

**SignalR Server:**
- **JWT с RSA подписью**
- Публичный ключ для верификации
- Время жизни токена: **30 минут**
- Маппинг: `SignalR JWT` ↔ `Laravel Token`

**Мобильное приложение:**
- Хранит только SignalR JWT токен
- Отправляет в заголовке всех запросов
- Автоматическое обновление при истечении

---

## 🔄 Синхронизация данных

### Hub Data Synchronization
```
SignalR Hub
    ↓
ObservableCollections (HubData)
    ↓
Binding в ViewModels
    ↓
UI обновления в реальном времени
```

### Обновление статусов
- Водитель начинает доставку → API обновляет статус
- SignalR получает обновление → HubData синхронизирует
- UI автоматически обновляет список

---

## 📦 Компоненты и Зависимости

### BaseLibrary (общая библиотека)
Используется всеми компонентами:
- **Shared Models** - Shipping, Driver, Incident, Chat, Message
- **Enums** - ShippingStatus, Rights, IncidentType
- **Auth** - JWT обработка
- **Tools** - Helper функции, GlobalOptions

Это обеспечивает единообразие структур данных между мобильным приложением и SignalR сервером.

---

## 🎯 Итоговая архитектура

| Компонент | Язык | Назначение | Порт |
|-----------|------|-----------|------|
| **IntegrationLabLaravelApi** | PHP 8.2 | RESTful API, управление данными | 8000 |
| **MobileSignalR** | C# .NET | Real-time Hub, auth relay | 5000 |
| **IntegrationLab** | C# Avalonia | Мобильное ПО для водителей | - |
| **MySQL** | SQL | Хранилище данных | 3306 |

Система спроектирована для:
- ✅ Real-time коммуникации между водителем и диспетчерским центром
- ✅ Управления доставками и маршрутами
- ✅ Обработки инцидентов и проблем
- ✅ Внутреннего обмена сообщениями (чаты)
- ✅ Кроссплатформенного доступа (Android/Desktop)
