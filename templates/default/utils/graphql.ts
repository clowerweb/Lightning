export const query = async (query: string, variables: Record<string, any> = {}) => {
  try {
    const response = await fetch('/api/process', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        query,
        variables,
      }),
    });
    return await response.json();
  } catch (error) {
    console.error('GraphQL request failed', error);
    return {
      error: 'Failed to connect to the GraphQL API',
      message: 'Please ensure the backend is running and the GraphQL endpoint is configured correctly.',
      timestamp: new Date().toISOString(),
    };
  }
};
