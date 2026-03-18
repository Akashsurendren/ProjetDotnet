using UnityEngine;

public class MenuController : MonoBehaviour
{
    public GameObject mainMenuPanel;
    public GameObject gameWorld;
    public GameObject settingsPanel; // Nouveau pour les paramètres

    void Start()
    {
        mainMenuPanel.SetActive(true);
        gameWorld.SetActive(false);
        if (settingsPanel != null) settingsPanel.SetActive(false);
    }

    public void StartGame()
    {
        mainMenuPanel.SetActive(false);
        gameWorld.SetActive(true);
    }

    public void OpenSettings()
    {
        mainMenuPanel.SetActive(false);
        settingsPanel.SetActive(true);
    }

    public void CloseSettings()
    {
        settingsPanel.SetActive(true); // On revient au menu
        mainMenuPanel.SetActive(false);
    }
}