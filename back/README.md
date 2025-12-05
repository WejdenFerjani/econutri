# Diabetes Prediction using CRISP-DM

This project aims to develop a machine learning model for predicting diabetes, following the CRISP-DM methodology. The final model is deployed using Flask.

## Project Overview

- **Objective**: Predict whether a patient has diabetes based on their medical data.
- **Methodology**: CRISP-DM (Cross-Industry Standard Process for Data Mining).
- **Technologies**: Python, Flask, Pandas, Scikit-learn, Matplotlib, Seaborn.
- **Models Tested**: Logistic Regression, K-Nearest Neighbors (KNN), Support Vector Machine (SVM), XGBoost, Gradient Boosting Classifier (GBC).
- **Final Model**: Gradient Boosting Classifier (GBC).

## Features

1. Data preprocessing and exploratory data analysis.
2. Evaluation of multiple machine learning models.
3. Deployment of the best-performing model (GBC) using Flask.

## CRISP-DM Phases

### 1. Business Understanding
Understand the goal: Predict diabetes using patient data.

### 2. Data Understanding
Analyze and visualize the dataset:
- Check for missing values.
- Study distributions and correlations.

### 3. Data Preparation
Prepare the data:
- Handle missing values.
- Normalize numerical features.
- Split the dataset into training and testing sets.

### 4. Modeling
Train and evaluate multiple models:
- Logistic Regression
- KNN
- SVM
- XGBoost
- GBC (selected for deployment)

### 5. Evaluation
Select the best model based on accuracy, precision, recall, and F1 score.

### 6. Deployment
Deploy the GBC model with Flask to make predictions via an API.

## Installation and Usage

1. Clone the repository:
   ```bash
   git clone https://github.com/Nourhen-Ferjeni/Diabetes-Prediction-CRISPDM.git
   cd Diabetes-Prediction-CRISPDM
